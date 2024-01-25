<?php

class MainWindow extends QMainWindow 
{    
    public function __construct($parent = null) 
    {
        parent::__construct($parent);
        $this->ui = setupUi($this);
    }

    public function edit1(){
        var_dump($this->ui->label->text);
        $this->ui->label->text="Build:v0.2a  PHP ver:".PHP_VERSION_ID;
        //phpinfo();
    }

    /**
     * @slot pushButton::clicked
     * @param object $sender
     */
    public function onPushButtonClicked($sender)
    {
        $srcDir=QFileDialog::getExistingDirectory($this);
        $this->ui->lineEdit->text=$srcDir;
        $this->ui->lineEdit_2->text=$srcDir."/result";
    }

    /**
     * @slot pushButton_2::clicked
     * @param object $sender
     */
    public function onPushButton_2Clicked($sender)
    {
        $dstDir=QFileDialog::getExistingDirectory($this);
        $this->ui->lineEdit_2->text=$dstDir;

    }

    /**
     * @slot pushButton_3::clicked
     * @param object $sender
     */
    public function onPushButton_3Clicked($sender)
    {
        mark_btn_run($this->ui->progressBar, $this->ui->checkBox, $this->ui->lineEdit->text, $this->ui->lineEdit_2->text, false);
    }

    /**
     * @slot pushButton_6::clicked
     * @param object $sender
     */
    public function onPushButton_6Clicked($sender)
    {
        mark_btn_run($this->ui->progressBar, $this->ui->checkBox, $this->ui->lineEdit->text, $this->ui->lineEdit_2->text, false, true);
    }

    /**
     * @slot pushButton_4::clicked
     * @param object $sender
     */
    public function onPushButton_4Clicked($sender)
    {
        mark_btn_run($this->ui->progressBar, $this->ui->checkBox, $this->ui->lineEdit->text, $this->ui->lineEdit_2->text);
    }

    /**
     * @slot pushButton_5::clicked
     * @param object $sender
     */
    public function onPushButton_5Clicked($sender)
    {
        mark_btn_run($this->ui->progressBar, $this->ui->checkBox, $this->ui->lineEdit->text, $this->ui->lineEdit_2->text, true, true, true);
    }
}
