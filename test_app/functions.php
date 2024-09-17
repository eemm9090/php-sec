<?php
require_once('connection.php');
session_start();

// エスケープ処理
function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// SESSIONにtokenを格納する
function setToken()
{
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

// SESSIONに格納されたtokenのチェックを行い、SESSIONにエラー文を格納する
function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

function unsetError()
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

//データ取得
function getTodoList()
{
    return getAllRecords();
}

//データ更新
function getSelectedTodo($id)
{
    return getTodoTextById($id);
}

//データ編集
function savePostedData($post)
{
    checkToken($post['token']);
    validate($post);
    $path = getRefererPath();
    switch ($path) {
        case '/new.php':
            createTodoData($post['content']);
            break;
        case '/edit.php':
            updateTodoData($post);
            break;
        case '/index.php': // 追記
          deleteTodoData($post['id']); // 追記
          break; // 追記
        default:
          break;
    }
}

function getRefererPath()
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);//グローバル変数$_SERVERに配列としてサーバー情報が入っているので、HTTP_REFERERで現在のページに遷移する前にユーザーエージェントが参照していたページのアドレスを取得
    return $urlArray['path'];
}

function validate($post)
{
    if (isset($post['content']) && $post['content'] === '') {
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}

?>