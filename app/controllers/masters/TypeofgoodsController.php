<?php
class TypeofgoodsController extends Controller {

    public function index(){
        $db = new Database();
        $result = $db->selectData('type_of_goods_master_t','*',[]);
        $this->viewWithLayout('masters/type_of_goods_master', ['title'=>'Type of Goods','result'=>$result]);
    }

    public function crudData($action='insertion'){
        header('Content-Type: application/json');
        $db = new Database();
        $table = 'type_of_goods_master_t';

        function clean($v){ return htmlspecialchars(trim($v),ENT_QUOTES,'UTF-8'); }

        // INSERT
        if($action=='insertion' && $_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                'goods_type'=>clean($_POST['goods_type']),
                'goods_short_name'=>clean($_POST['goods_short_name']),
                'display'=>($_POST['display']=='N')?'N':'Y',
                'created_by'=>1,'updated_by'=>1
            ];

            if(empty($data['goods_type'])){
                echo json_encode(['success'=>false,'message'=>'Goods Name required']); exit;
            }

            $ins = $db->insertData($table,$data);
            echo json_encode($ins ? ['success'=>true,'message'=>'Goods added ✅'] : ['success'=>false,'message'=>'Insert failed ❌']);
            exit;
        }

        // UPDATE
        if($action=='updation' && $_SERVER['REQUEST_METHOD']=='POST'){
            $id = intval($_GET['id']);
            $data = [
                'goods_type'=>clean($_POST['goods_type']),
                'goods_short_name'=>clean($_POST['goods_short_name']),
                'display'=>$_POST['display'],
                'updated_by'=>1
            ];

            $up = $db->updateData($table,$data,['id'=>$id]);
            echo json_encode($up ? ['success'=>true,'message'=>'Updated ✅'] : ['success'=>false,'message'=>'Update failed ❌']);
            exit;
        }

        // DELETE
        if($action=='deletion'){
            $id = intval($_GET['id']);
            $del = $db->deleteData($table,['id'=>$id]);
            echo json_encode($del ? ['success'=>true,'message'=>'Deleted ✅'] : ['success'=>false,'message'=>'Delete failed ❌']);
            exit;
        }

        echo json_encode(['success'=>false,'message'=>'Invalid request']);
    }

    public function getGoodsById(){
        header('Content-Type: application/json');
        $id = intval($_GET['id']);
        $db = new Database();
        $row = $db->selectData('type_of_goods_master_t','*',['id'=>$id]);

        echo json_encode(!empty($row)
            ? ['success'=>true,'data'=>$row[0]]
            : ['success'=>false,'message'=>'Not found']
        );
    }
}
?>
