<?php
    class Utilities {
        public function getPaging($page, $total_rows, $recodrs_per_page, $page_url){
            //paging array
            $paging_arr = array();

            //button for first page
            $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";

            //count all product in database to calculate total pages
            $total_pages = ceil($total_rows / $recodrs_per_page);

            // range of links to show
            $range = 2;

            // display links to range of pages around current page
            $initial_num = $page - $range;
            $conditional_limit_num = ($page + $range) + 1;

            $paging_arr['pages'] = array();
            $page_count = 0;

            for($x = $initial_num; $x < $conditional_limit_num; $x++){
                if(($x > 0) && ($x <= $total_pages)) {
                    $paging_arr['pages'][$page_count]['page'] = $x;
                    $paging_arr['pages'][$page_count]['url'] = "{$page_url}page={$x}";
                    $paging_arr['pages'][$page_count]['current_page'] = $x ==$page ? "yes" : "no";

                    $page_count++;
                }
            }

            // button for last page
            $paging_arr['last'] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";

            // return result in json format
            return $paging_arr;
        }
    }