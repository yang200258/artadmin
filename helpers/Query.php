<?php
namespace app\helpers;

class Query {

    public static function getSpiderRes($name, $id_card)
    {
        $url = "http://119.253.58.48:9999/artexam/pages/student/studentSearch/searchStudentResultList.jsp?stuName={$name}&idCardNo={$id_card}&cerNo=";
        $curl = new Curl();
        $page = $curl->get($url);
        $html = new simple_html_dom();
        $html->load($page);
        $dom = $html->find('.kscxlist li a');
        if (!$dom)
        {
            return [];
        }
        $res = [];
        $data = [];
        foreach ($dom as $one)
        {
            $attr = $one->attr;
            if (!$attr)
            {
                continue;
            }
            $href = $one->attr['href'];
            $d_url = 'http://119.253.58.48:9999/artexam/pages/student/studentSearch/' . $href;
            $d_page = $curl->get($d_url);
            $html->load($d_page);
            $d_dom = $html->find('.kscxform form input');
            if (!$d_dom)
            {
                continue;
            }
            $res['name'] = $d_dom[0]->attr['value'];
            $res['sex'] = $d_dom[1]->attr['value'];
            $res['birth'] = $d_dom[2]->attr['value'];
            $res['nation'] = $d_dom[3]->attr['value'];
            $res['nationality'] = $d_dom[4]->attr['value'];
            $res['id_number'] = $d_dom[5]->attr['value'];

            $res['organ_name'] = $d_dom[6]->attr['value'];
            $res['domain'] = $d_dom[7]->attr['value'];
            $res['start_stop'] = $d_dom[8]->attr['value'];
            $res['class'] = $d_dom[9]->attr['value'];
            $res['issue_date'] = $d_dom[10]->attr['value'];
            $res['certificate_number'] = $d_dom[11]->attr['value'];
            $data[] = $res;
        }
        return $data;
    }
}