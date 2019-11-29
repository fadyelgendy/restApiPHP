<?php
    //Show errors
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    //home page url
    $home_url = "http://localhost/RESTful-_API_PHP-master/";

    //page given in url parameter default page is ( 1 )
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    //set number of records of page
    $records_per_page = 5;

    // calculate for the query LIMIt clause
    $form_record_num = ($records_per_page * $page) - $records_per_page;


