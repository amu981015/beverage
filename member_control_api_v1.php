<?php
const DB_SERVER = "localhost";
const DB_USERNAME = "admin";
const DB_PASSWORD = "123456";
const DB_NAME = "shop";

function create_connection()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        echo json_encode(["state" => false, "message" => "連線失敗!"]);
        exit;
    }
    return $conn;
}
function get_json_input()
{
    $data = file_get_contents("php://input");
    return json_decode($data, true);
}

function respond($state, $message, $data = null)
{
    echo json_encode(["state" => $state, "message" => $message, "data" => $data]);
}

function register_user()
{
    $input = get_json_input();
    if (isset($input["username"]) & isset($input["password"]) & isset($input["email"])) {
        $p_username = $input["username"];
        $p_password = password_hash(trim($input["password"]), PASSWORD_DEFAULT);
        $p_email = trim($input["email"]);
        if ($p_username && $p_password && $p_email) {
            $conn = create_connection();

            $stmt = $conn->prepare("INSERT INTO users(username,password,email) VALUES(?,?,?)");
            $stmt->bind_param("sss", $p_username, $p_password, $p_email);
            if ($stmt->execute()) {
                $uid01 = substr(hash('md5', time()), 10, 4) . substr(bin2hex(random_bytes(16)), 4, 4) . substr(hash('sha256', time()), 10, 4) . substr(hash('sha512', time()), 10, 4);
                $updata_stmt = $conn->prepare("UPDATE users SET Uid01 = ? WHERE username = ?");
                $updata_stmt->bind_param("ss", $uid01, $p_username);
                if ($updata_stmt->execute()) {

                    $user_stmt = $conn->prepare("SELECT Uid01 FROM users WHERE username = ?");
                    $user_stmt->bind_param("s", $p_username);
                    $user_stmt->execute();
                    $user_data = $user_stmt->get_result()->fetch_assoc();
                    respond(true, "註冊成功", $user_data);
                } else {
                    respond(false, "登入失敗，UID更新失敗");
                }
            } else {
                respond(false, "註冊失敗");
            }
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function login_user()
{
    $input = get_json_input();
    if (isset($input["username"]) && isset($input["password"])) {
        $p_username = trim($input["username"]);
        $p_password = trim($input["password"]);
        if ($p_username && $p_password) {
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $p_username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($p_password, $row["password"])) {
                    $uid01 = substr(hash('md5', time()), 10, 4) . substr(bin2hex(random_bytes(16)), 4, 4) . substr(hash('sha256', time()), 10, 4) . substr(hash('sha512', time()), 10, 4);
                    $updata_stmt = $conn->prepare("UPDATE users SET Uid01 = ? WHERE username = ?");
                    $updata_stmt->bind_param("ss", $uid01, $p_username);
                    if ($updata_stmt->execute()) {

                        $user_stmt = $conn->prepare("SELECT store_id, username, email, vip_level, order_count, Uid01, created_at FROM users WHERE username = ?");
                        $user_stmt->bind_param("s", $p_username);
                        $user_stmt->execute();
                        $user_data = $user_stmt->get_result()->fetch_assoc();
                        respond(true, "登入成功", $user_data);
                    } else {
                        respond(false, "登入失敗，UID更新失敗");
                    }
                } else {
                    respond(false, "登入失敗，密碼錯誤");
                }
            } else {
                respond(false, "登入失敗，該帳號不存在");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function check_uid()
{

    $input = get_json_input();
    if (isset($input["uid01"])) {
        $p_uid01 = trim($input["uid01"]);
        if ($p_uid01) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT user_id, store_id, username, email, vip_level, order_count, Uid01, created_at FROM users WHERE Uid01 = ?");
            $stmt->bind_param("s", $p_uid01);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $userdata = $result->fetch_assoc();
                respond(true, "驗證成功", $userdata);
            } else {
                respond(false, "驗證失敗");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function check_uni()
{
    $input = get_json_input();
    if (isset($input["username"])) {
        if ($input["username"] != "") {
            $p_username = trim($input["username"]);
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
            $stmt->bind_param("s", $p_username);
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) == 1) {
                respond(false, "帳號已存在，不可使用");
            } else {
                respond(true, "帳號不存在，可使用");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空白");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function get_all_user_data()
{
    $conn = create_connection();

    $stmt = $conn->prepare("SELECT u.store_id,s.name,u.username,u.email FROM users u JOIN stores s ON u.store_id = s.store_id WHERE u.store_id IS NOT NULL ORDER BY user_id ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得所有店長資料成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}





function get_all_storeData_data()
{
    $conn = create_connection();

    $stmt = $conn->prepare("SELECT * FROM stores");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得所有門市資料成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}

function get_all_up_MenuData_data()
{
    $conn = create_connection();

    $stmt = $conn->prepare("SELECT * FROM menus WHERE status = '上架'");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得上架菜單資料成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}

function get_all_MenuData_data()
{
    $conn = create_connection();

    $stmt = $conn->prepare("SELECT * FROM menus");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得上架菜單資料成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}

function selectcategory_data()
{

    $conn = create_connection();

    $stmt = $conn->prepare("SELECT DISTINCT category FROM menus");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得菜單分類成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}
function selectcity_data()
{
    $conn = create_connection();

    $stmt = $conn->prepare("SELECT DISTINCT city FROM stores");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得城市成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}

function selectarea_data()
{
    $input = get_json_input();
    if (isset($input["city"])) {
        if ($input["city"] != "") {
            $p_city = trim($input["city"]);
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT DISTINCT area FROM stores WHERE city = ?");
            $stmt->bind_param("s", $p_city);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $mydata = array();
                while ($row = $result->fetch_assoc()) {
                    $mydata[] = $row;
                }
                respond(true, "取得區域成功", $mydata);
            } else {
                respond(false, "查無資料");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空白");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function selectstore_data()
{
    $input = get_json_input();
    if (isset($input["city"]) && isset($input["area"])) {
        if ($input["city"] != "" && $input["area"] != "") {
            $p_city = trim($input["city"]);
            $p_area = trim($input["area"]);
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT * FROM stores WHERE city = ? AND area = ?");
            $stmt->bind_param("ss", $p_city, $p_area);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $mydata = array();
                while ($row = $result->fetch_assoc()) {
                    $mydata[] = $row;
                }
                respond(true, "取得門市資料成功", $mydata);
            } else {
                respond(false, "查無資料");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空白");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function selectMenu_formdata_data()
{
    $input = get_json_input();
    if (isset($input["category"])) {
        $p_category = trim($input["category"]);
        if ($p_category) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT * FROM menus WHERE category = ?");
            $stmt->bind_param("s",  $p_category);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $mydata = array();
                while ($row = $result->fetch_assoc()) {
                    $mydata[] = $row;
                }
                respond(true, "取得菜單資料成功", $mydata);
            } else {
                respond(false, "查無資料");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function get_orderdata()
{
    $input = get_json_input();
    if (isset($input["store_id"])) {
        $p_storeId = trim($input["store_id"]);
        if ($p_storeId) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT o.order_id,s.name,o.status,m.name,od.quantity,o.total_price,o.order_date FROM orders o JOIN order_details od ON o.order_id = od.order_id JOIN menus m ON od.menu_id = m.menu_id JOIN  stores s ON o.store_id = s.store_id WHERE o.store_id = ? ORDER BY o.order_id, od.detail_id");
            $stmt->bind_param("s",  $p_storeId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $mydata = array();
                while ($row = $result->fetch_assoc()) {
                    $mydata[] = $row;
                }
                respond(true, "取得商店訂單資料成功", $mydata);
            } else {
                respond(false, "查無資料");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}






function edit_orderstatusdata()
{
    $input = get_json_input();
    if (isset($input["order_id"]) && isset($input["status"])) {
        $p_orderId = trim($input["order_id"]);
        $p_status = trim($input["status"]);
        if ($p_orderId && $p_status) {
            $conn = create_connection();

            if ($p_status == "已支付") {
                $stmt = $conn->prepare("UPDATE orders SET status = '已支付' WHERE order_id = ?");
            } else if ($p_status == "已取消") {
                $stmt = $conn->prepare("UPDATE orders SET status = '已取消' WHERE order_id = ?");
            } else if ($p_status == "待支付") {
                $stmt = $conn->prepare("UPDATE orders SET status = '待支付' WHERE order_id = ?");
            }
            $stmt->bind_param("s", $p_orderId);
            if ($stmt->execute()) {
                if ($stmt->affected_rows === 1) {
                    respond(true, "訂單狀態更新成功");
                } else {
                    respond(false, "訂單狀態更新失敗,並無更新行為");
                }
            } else {
                respond(false, "訂單狀態更新失敗");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function userGetOrderData()
{
    $input = get_json_input();
    if (isset($input["user_id"])) {
        $p_userId = trim($input["user_id"]);
        if ($p_userId) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT o.order_id, o.status, o.total_price, o.order_date, s.name, s.address FROM orders o JOIN stores s ON o.store_id = s.store_id WHERE o.user_id = ?");
            $stmt->bind_param("s",  $p_userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $mydata = array();
                while ($row = $result->fetch_assoc()) {
                    $mydata[] = $row;
                }
                respond(true, "取得用戶訂單資料成功", $mydata);
            } else {
                respond(false, "查無資料");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function userGetOrderDetailData()
{
    $input = get_json_input();
    if (isset($input["order_id"])) {
        $p_orderId = trim($input["order_id"]);
        if ($p_orderId) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT m.name,m.price,od.quantity FROM order_details od JOIN menus m ON od.menu_id = m.menu_id WHERE od.order_id = ?");
            $stmt->bind_param("s",  $p_orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $mydata = array();
                while ($row = $result->fetch_assoc()) {
                    $mydata[] = $row;
                }
                respond(true, "取得用戶訂單資料成功", $mydata);
            } else {
                respond(false, "查無資料");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function create_order()
{
    $input = get_json_input();
    if (isset($input["user_id"]) && isset($input["store_id"]) && isset($input["total_price"]) && isset($input["order_details"])) {
        $user_id = $input["user_id"];
        $store_id = $input["store_id"];
        $total_price = $input["total_price"];
        $order_details = $input["order_details"];
        if ($user_id && $store_id && $total_price && count($order_details) > 0) {
            $conn = create_connection();

            $conn->begin_transaction();

            try {
                $stmt = $conn->prepare("INSERT INTO orders (store_id, user_id, total_price) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $store_id, $user_id, $total_price);

                if ($stmt->execute()) {
                    $order_id = $conn->insert_id;
                    foreach ($order_details as $detail) {
                        $menu_id = $detail["menu_id"];
                        $quantity = $detail["quantity"];

                        $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, menu_id, quantity) VALUES (?, ?, ?)");
                        $stmt_detail->bind_param("sss", $order_id, $menu_id, $quantity);

                        if (!$stmt_detail->execute()) {
                            throw new Exception("訂單明細插入失敗");
                        }
                    }

                    $conn->commit();
                    respond(true, "訂單處理成功");
                } else {
                    throw new Exception("訂單插入失敗");
                }
            } catch (Exception $e) {
                $conn->rollback();
                respond(false, $e->getMessage());
            } finally {
                $conn->close();
            }
        } else {
            respond(false, "必填欄位不能為空或訂單明細無效");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function createmenuData()
{
    $input = get_json_input();
    if (isset($input["name"]) &  isset($input["price"]) & isset($input["category"]) & isset($input["status"])) {
        $p_name = trim($input["name"]);
        $p_price = trim($input["price"]);
        $p_category = trim($input["category"]);
        $p_status = trim($input["status"]);
        if ($p_name && $p_price && $p_category && $p_status) {
            $conn = create_connection();
            $stmt = $conn->prepare("INSERT INTO menus(name,price,category,status) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss",  $p_name, $p_price, $p_category, $p_status);
            if ($stmt->execute()) {
                respond(true, "菜單新增成功");
            } else {
                respond(false, "菜單新增失敗，欄位錯誤");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function editmenuData()
{
    $input = get_json_input();
    if (isset($input["menu_id"]) & isset($input["name"]) &  isset($input["price"]) & isset($input["category"]) & isset($input["status"])) {
        $p_menu_id = trim($input["menu_id"]);
        $p_name = trim($input["name"]);
        $p_price = trim($input["price"]);
        $p_category = trim($input["category"]);
        $p_status = trim($input["status"]);
        if ($p_menu_id && $p_name && $p_price && $p_category && $p_status) {
            $conn = create_connection();
            $stmt = $conn->prepare("UPDATE menus SET name = ?, price = ?, category = ?, status = ? WHERE menu_id = ?");
            $stmt->bind_param("sssss",  $p_name, $p_price, $p_category, $p_status, $p_menu_id);
            if ($stmt->execute()) {
                respond(true, "菜單更新成功");
            } else {
                respond(false, "菜單更新失敗，欄位錯誤");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function checkMenuUni()
{
    $input = get_json_input();
    if (isset($input["name"])) {
        if ($input["name"] != "") {
            $p_name = trim($input["name"]);
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT name FROM menus WHERE name = ?");
            $stmt->bind_param("s", $p_name);
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) == 1) {
                respond(false, "飲料名稱已存在，不可使用");
            } else {
                respond(true, "飲料名稱不存在，可使用");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空白");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function checkStoreUni()
{
    $input = get_json_input();
    if (isset($input["store_id"])) {
        if ($input["store_id"] != "") {
            $p_storeId = trim($input["store_id"]);
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT name FROM stores WHERE store_id = ?");
            $stmt->bind_param("s", $p_storeId);
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) == 1) {
                if ($result->num_rows > 0) {
                    $mydata = $result->fetch_assoc();
                    respond(true, "商店代碼存在，商店代稱：", $mydata);
                }
            } else {
                respond(false, "商店代碼不存在，不可使用");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空白");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function createAdminData()
{
    $input = get_json_input();
    if (isset($input["store_id"]) &  isset($input["username"]) & isset($input["password"]) & isset($input["email"])) {
        $p_storeId = trim($input["store_id"]);
        $p_username = trim($input["username"]);
        $p_password = password_hash(trim($input["password"]), PASSWORD_DEFAULT);
        $p_email = trim($input["email"]);
        if ($p_storeId && $p_username && $p_password && $p_email) {
            $conn = create_connection();
            $stmt = $conn->prepare("INSERT INTO users(store_id,username,password,email,vip_level) VALUES(?,?,?,?,100)");
            $stmt->bind_param("ssss",  $p_storeId, $p_username, $p_password, $p_email);
            if ($stmt->execute()) {
                respond(true, "新增店長成功");
            } else {
                respond(false, "店長新增失敗，欄位錯誤");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function editAdminData()
{
    $input = get_json_input();
    if (isset($input["store_id"]) & isset($input["username"])) {
        $p_storeId = trim($input["store_id"]);
        $p_username = trim($input["username"]);
        if ($p_storeId && $p_username) {
            $conn = create_connection();
            $stmt = $conn->prepare("UPDATE users SET store_id = ? WHERE username = ?");
            $stmt->bind_param("ss",  $p_storeId, $p_username);
            if ($stmt->execute()) {
                respond(true, "修改店長資料成功");
            } else {
                respond(false, "修改店長資料失敗，欄位錯誤");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function deleteAdminData()
{
    $input = get_json_input();
    if (isset($input["username"])) {
        $p_username = trim($input["username"]);
        if ($p_username) {
            $conn = create_connection();
            $stmt = $conn->prepare("UPDATE users SET store_id = NULL,vip_level = 0 WHERE username = ?");
            $stmt->bind_param("s", $p_username);
            if ($stmt->execute()) {
                respond(true, "註銷店長資料成功");
            } else {
                respond(false, "註銷店長資料失敗，欄位錯誤");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不能為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_GET["action"] ?? "";
    switch ($action) {
        case "register":
            register_user();
            break;
        case "login":
            login_user();
            break;
        case "checkuid":
            check_uid();
            break;
        case "checkuni":
            check_uni();
            break;
        case "createorder":
            create_order();
            break;
        case "menudata":
            selectMenu_formdata_data();
            break;
        case "selectarea":
            selectarea_data();
            break;
        case "selectstore":
            selectstore_data();
            break;
        case "usergetorder":
            userGetOrderData();
            break;
        case "usergetdetailorder":
            userGetOrderDetailData();
            break;
        case "createmenu":
            createmenuData();
            break;
        case "editmenu":
            editmenuData();
            break;
        case "checkmenuuni":
            checkMenuUni();
            break;
        case "getorderdata":
            get_orderdata();
            break;
        case "editorderstatusdata":
            edit_orderstatusdata();
            break;
        case "checkstoreuni":
            checkStoreUni();
            break;
        case "createadmindata":
            createAdminData();
            break;
        case "editadmindata":
            editAdminData();
            break;
        case "deleteadmindata":
            deleteAdminData();
            break;

        default;
            respond(false, "無效的操作");
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET["action"] ?? "";
    switch ($action) {
        case "storedata":
            get_all_storeData_data();
            break;
        case "allupmenu":
            get_all_up_MenuData_data();
            break;
        case "allmenu":
            get_all_MenuData_data();
            break;
        case "category":
            selectcategory_data();
            break;
        case "selectcity":
            selectcity_data();
            break;
        case "getalluser":
            get_all_user_data();
            break;
        default;
            respond(false, "無效的操作");
    }
} else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $action = $_GET["action"] ?? "";
    switch ($action) {
        default;
            respond(false, "無效的操作");
    }
} else {
    respond(false, "無效的請求方法");
}
