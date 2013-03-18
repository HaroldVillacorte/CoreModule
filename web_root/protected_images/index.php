<?php if (!isset($_COOKIE['CI_Starter_login'])) exit('No direct script access allowed');

if (isset($_GET['image']))
{
    $array = explode('.', $_GET['image']);
    $ext = $array[1];

    if (!file_exists($_GET['image']))
    {
        exit ('File not found.');
    }
    else
    {
        header('Content-Type: image/' . $ext);
        return readfile($_GET['image']);
    }
}
else
{
    exit ('Invalid request.');
}
