<!-- các hàm xử lý liên quan đến cơ sở dữ liệu -->
<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

function query($sql, $data = [], $check = false)
{
    global $conn;
    $ketqua = false;

    try {
        $statement = $conn->prepare($sql);

        if (!empty($data)) {
            $ketqua = $statement->execute($data);
        } else {
            $ketqua = $statement->execute();
        }
    } catch (Exception $exp) {

        echo $exp->getMessage() . '<br>';
        echo 'File: ' . $exp->getFile() . '<br>';
        echo 'Line: ' . $exp->getLine();
        die();
    }

    if ($check) {
        return $statement;
    }

    return $ketqua;
}

function insert($table, $data)
{
    $key = array_keys($data);
    $truong = implode(',', $key);

    $value_tb = ':' . implode(',:', $key);

    $sql = "INSERT INTO $table ($truong) VALUES ($value_tb)";

    $kq = query($sql, $data);

    return $kq;
}

function update($table, $data, $condition = '')
{

    $update = '';

    foreach ($data as $key => $value) {
        $update .= $key . '=:' . $key . ',';
    }
    $update = trim($update, ',');

    if (!empty($condition)) {
        $sql = "UPDATE $table SET $update WHERE $condition";
    } else {
        $sql = "UPDATE $table SET $update";
    }

    $kq = query($sql, $data);

    return $kq;
}

function delete($table, $condition = '')
{

    if (!empty($condition)) {
        $sql = "DELETE FROM $table WHERE $condition";
    } else {
        $sql = "DELETE FROM $table";
    }

    $kq = query($sql);

    return $kq;
}

function selectAll($sql)
{

    $kq = query($sql, '', true);
    if (is_object($kq)) {
        $dataFetch = $kq->fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}

function selectOne($sql)
{

    $kq = query($sql, '', true);
    if (is_object($kq)) {
        $dataFetch = $kq->fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}

// function selectByCondition($sql, $condition = '')
// {
//     if (!empty($condition)) {
//         $sql = "$sql WHERE $condition";
//         $kq = query($sql, '', true);
//         if (is_object($kq)) {
//             $dataFetch = $kq->fetchAll(PDO::FETCH_ASSOC);
//         }
//     } else {
//         $kq = query($sql, '', true);
//         if (is_object($kq)) {
//             $dataFetch = $kq->fetchAll(PDO::FETCH_ASSOC);
//         }
//     }
//     return $dataFetch;
// }

function getCountRows($sql)
{
    $kq = query($sql, '', true);
    if (!empty($kq)) {
        return $kq->rowCount();
    }
}
