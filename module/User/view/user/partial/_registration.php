<?php
$this->form->prepare();
$this->form()->openTag($this->form);
$this->flashManager()->display();

$this->form->get('login');