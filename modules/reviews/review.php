<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

$filterAll = filter();
$errors = [];

if (!empty($filterAll['userId']) && !empty($filterAll['productId'])) {
    $userId = $filterAll['userId'];
    $productId = $filterAll['productId'];
    $content = $filterAll['content'];
    $stars  = $filterAll['stars'];

    $dataInsertReview = [
        'content' =>  $content,
        'stars' => $stars,
        'id_product' => $productId,
        'id_user' => $userId
    ];
    insert('reviews', $dataInsertReview);
    redirect('?module=home&action=productDetail&productId=' . $productId . '&userId=' . $userId . '&role=0');
}
