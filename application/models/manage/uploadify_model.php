<?php
class Uploadify_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	function queryData() {
		$query = $this->db->query ( "SELECT id,name,( CASE pid WHEN '0' THEN '' ELSE pid END) as _parentId,type,isValid,createTime FROM menus" );
        return json_encode(array(
            'total' => $query->num_rows,
            'rows'  => $query->result_array(),
        ));
	}
	function add($attachmentName,$attachmentPath) {
        $data = array(
            'attachmentName' => $attachmentName,
            'attachmentPath' => $attachmentPath
        );
        $this->db->insert('attachment', $data);
        $attachmentID=$this->db->insert_id();
        return json_encode(array(
            "type"=>"1",
            "attachmentID"=>$attachmentID,
            "attachmentName"=>$attachmentName,
            "attachmentPath"=>$attachmentPath
        ));
    }
    function delete($ids) {
        $this->db->query ( "DELETE FROM attachment WHERE attachmentID IN ($ids)" );
    }
    function getAttachment($newsid)
    {
        if(! empty ($newsid)){
            $query = $this->db->query ( "SELECT attachmentID,attachmentPath,attachmentName FROM attachment where newsid='".$newsid."'" );
            try{
                 $rows= $query->result_array();
                if(count($rows)==1){
                    $row=$rows[0];
                return json_encode(array(
                    "type"=>"1",
                    "attachmentID"=>$row['attachmentID'],
                    "attachmentName"=>$row['attachmentName'],
                    "attachmentPath"=>$row['attachmentPath']
                ));
                }
            }catch (Exception $e){
                return json_encode(array(
                    "type"=>"0",
                    "errMessage"=>"无附件"
                ));
            }
        }
        return json_encode(array(
            "type"=>"0",
            "errMessage"=>"无附件"
        ));
    }

}