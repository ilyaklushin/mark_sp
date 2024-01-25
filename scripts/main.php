<?php
error_reporting(E_ALL);

function a_log($a_str, &$_currObj){
    var_dump($_currObj->ui->tableWidget);
    $_currObj->ui->tableWidget->addRow();
    //$w->ui->tableWidget->set
    //$w->ui->textBrowser->text=$a_str."\n".$w->ui->textBrowser->text;
}

require_once("qrc://scripts/mark.php");
require_once("qrc://scripts/mainwindow.php");

$app = new QApplication($argc, $argv);

$w = new MainWindow;
$w->show();
$w->edit1();

return $app->exec();
