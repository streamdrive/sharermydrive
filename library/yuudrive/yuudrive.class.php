<?php

/**
 * ====================================================================================
 * @author MuhBayu
 * @link https://github.com/MuhBayu
 * @package YuuDrive Database Library
 */

namespace YuuDrive;
use PDO;
use FluentPDO;

defined('BASEPATH') OR exit('No direct script access allowed');
class YuuDrive_db
{
	private $max_file_id = 6;
	public $CURRENT_DATE;
	public $DATETIME;
	private $pdo;
	function __construct()
	{
		global $dbinfo;
		$this->pdo = new PDO("mysql:dbname=$dbinfo[db]", "$dbinfo[user]", "$dbinfo[password]");
		$this->db = new FluentPDO($this->pdo);
		// date_default_timezone_set("Asia/Jakarta");
		$this->DATETIME = date("Y-m-d H:i:s");
		$this->CURRENT_DATE = date("Y-m-d");
	}
	function __destruct() {
		$this->pdo = null;
		$this->db = null;
	}
/*
|--------------------------------------------------------------------------
| GET Function
|--------------------------------------------------------------------------
*/
	public function insert_file($data) {
		$query = $this->db
				 ->insertInto('tb_file')
				 ->values($data)->execute();
		return Self::get_file($data['id']);
	}
	public function insert_mirror($data) {
		return $this->db->insertInto('tb_mirrors')->values($data)->execute();
	}
	public function addAccount($data) {
		$query = $this->db
				->insertInto('tb_user')
				->values($data);
		return $query->execute();
	}
	public function getUser($email) {
		$query = $this->db
				->from('tb_user')
				->where('email', $email)
				->execute();
		return $query->fetch();
	}

/*
|--------------------------------------------------------------------------
| GET Function
|--------------------------------------------------------------------------
*/
	public function get_option($option_name) {
		$query = $this->db
				 ->from('tb_options')
				 ->where('option_name', $option_name);
		$result = $query->execute()->fetch();
		if($result) {
			return $result['option_value'];
		} else {
			return null;
		}
	}
	public function get_file_exist($file_id) {
		$query = $this->db
				 ->from('tb_file')
				 ->select(null)
				 ->select('id')
				 ->select('file_name')
				 ->where('file_id', $file_id);
		return ($query->execute()) ? $query->fetch() : false;
	}
	// public function get_file($file_id) {
	// 	$query = $this->db
	// 			 ->from('tb_file')
	// 			 ->where('id', $file_id);
	// 	return $query->execute()->fetch();
	// }
	public function get_file($file_id) {
		try {
			$query = $this->pdo->prepare("SELECT *,
					(SELECT alias FROM tb_mirrors as m WHERE m.file_id=f.id AND m.hoster='multiup') as mirror_multiup FROM tb_file as f WHERE id=?");
		  $query->execute([$file_id]);
		  return $query->fetch();
		} catch(\Exception $e) {
			 return false;
		}
  }
	public function get_file_count_statistic() {
		$query = $this->pdo->query('Select COUNT(id) as 
		count_today, (Select COUNT(id) FROM tb_file WHERE DATE(created_date) = CURDATE() - INTERVAL 1 DAY) as count_yesterday 
		FROM tb_file WHERE DATE(created_date) = CURRENT_DATE()');
		return (object) $query->fetch();
	}
	public function get_all_files($offset = 0, $limit = 20) {
		$query = $this->db
				 ->from('tb_file')
				 ->offset($offset)
				 ->limit($limit)
				 ->orderBy('created_date DESC')
				 ->execute();
		$datas['count'] = $query->rowCount();
		$datas['files'] = $query->fetchAll();
		return $datas;
	}
	// public function get_file_from_user2($email, $offset = 0, $limit = 20) {
	// 	$query = $this->db
	// 			 ->from('tb_file')
	// 			 ->offset($offset)
	// 			 ->limit($limit)
	// 			 ->orderBy('created_date DESC')
	// 			 ->where('file_owner_mail', $email)
	// 			 ->execute();
	// 	$file['count'] = $query->rowCount();
	// 	$file['files'] = $query->fetchAll();
	// 	return $file;
	// }
	public function get_file_from_user($email, $offset = 0, $limit = 20) {
		try {
			$query = $this->pdo->prepare("SELECT *,
				 (SELECT alias FROM tb_mirrors as m WHERE m.file_id=f.id AND m.hoster='multiup') as mirror_multiup FROM tb_file as f WHERE f.file_owner_mail = :email ORDER BY f.created_date DESC LIMIT $limit OFFSET $offset");
		$query->execute([':email' => $email]);
		$datas['count'] = $query->rowCount();
		$datas['files'] = $query->fetchAll(\PDO::FETCH_ASSOC);
		return $datas;
		} catch(\Exception $e) {
				return false;
		}
}
	// public function get_last_dls() {
	// 	$query = $this->db
	// 			 ->from('tb_lastdls')
	// 			 ->orderBy('download_at')
	// 			 ->execute();
	// 	return $query->fetchAll();
	// }
	public function get_last_dls() {
		$query = $this->db
				 ->from('tb_lastdls as l')
				 ->select(null)
				 ->select('l.*, f.file_name')
				 ->join('tb_file as f ON f.id = l.id')
				 ->orderBy('download_at DESC')
				 ->execute();
		return $query->fetchAll();
	}
	public function get_last_registered() {
		$query = $this->db
				 ->from('tb_user as u')
				 ->orderBy('join_date DESC')
				 ->select(null)
				 ->select('u.id_user, u.name, u.email, u.join_date')
				 ->offset(0)
				 ->limit(10)
				 ->execute();
		return $query->fetchAll();
	}
	public function get_last_registered_count() {
		$query = $this->pdo->query('Select COUNT(id_user) as 
		count_today, (Select COUNT(id_user) FROM tb_user WHERE DATE(join_date) = CURDATE() - INTERVAL 1 DAY) as count_yesterday 
		FROM tb_user WHERE DATE(join_date) = CURRENT_DATE()');
		return (object) $query->fetch();
	}
	public function get_top_users($orderBy='total_file') {
		$query = $this->pdo->query('SELECT SUM(downloads) as total_download, COUNT(id) as total_file, SUM(file_size) as total_storage, 
		file_owner_mail as email, u.name, u.join_date 
		FROM `tb_file` INNER JOIN tb_user as u ON u.email = tb_file.file_owner_mail GROUP BY file_owner_mail ORDER by '.$orderBy.' DESC LIMIT 20');
		return $query->fetchAll(\PDO::FETCH_OBJ);
	}
	public function get_most_downloads() {
		$query = $this->db
				 ->from('tb_file')
				 ->limit(10)
				 ->select('tb_file.*, tb_lastdls.download_at')
				 ->leftJoin('tb_lastdls ON tb_lastdls.id = tb_file.id')
				 ->orderBy('downloads DESC')
				 ->execute();
		return $query->fetchAll();
	}
	public function get_count($tbl, $user='') {
		$where='';
		if($user) $where = "file_owner_mail";
		$query = $this->db
				 ->from($tbl)
				 ->where($where, $user)
				 ->execute();
		return $query->rowCount();
	}
	public function get_all_filesize($user='') {
		$where="";
		if($user) $where = "file_owner_mail";
		$query = $this->db
				 ->from('tb_file')
				 ->select(null)
				 ->select('file_size')
				 ->where($where, $user)
				 ->execute();
		foreach ($query->fetchAll() as $key => $r) {
			$data[] = $r['file_size'];
		}
		return (@$data) ? array_sum($data) : 0;
	}
	public function get_all_downloaded() {
		$query = $this->db
				 ->from('tb_file')
				 ->select(null)
				 ->select('downloads')
				 ->execute();
		foreach ($query->fetchAll() as $key => $r) {
			$data[] = $r['downloads'];
		}
		return (@$data) ? array_sum($data) : 0;
	}
	public function broken_file($data) {
		$query = $this->db
				 ->insertInto('tb_broken')
				 ->values($data);
		return $query->execute();
	}
	public function get_broken_file($file_owner) {
		$query = $this->db->from('tb_file')
				->innerJoin('tb_broken ON tb_broken.id = tb_file.id')
				->select('tb_broken.created_date')
				->select('tb_broken.type')
				->where(null)
				->where('tb_file.file_owner_mail', $file_owner)
				->orderBy('tb_broken.created_date DESC')
				->execute();
		$broken['count'] = $query->rowCount();
		$broken['files'] = $query->fetchAll();
		return $broken;
	}


/*
|--------------------------------------------------------------------------
| UPDATE Function
|--------------------------------------------------------------------------
*/
	public function update_filename($file_id, $new_name) {
		$new_name = $this->sanitizeString($new_name);
		$query = $this->db
				->update('tb_file')
				->set(array('file_name' => $new_name))
				->where('id', $file_id);
		return $query->execute();
	}
	public function update_option($option_name, $option_value=null) {
		$check = $this->db
				 ->from('tb_options')
				 ->where('option_name', $option_name)->execute()->rowCount();
		if($check > 0) {
			return $this->db
						  ->update('tb_options')
						  ->set(array('option_value' => $option_value))
						  ->where('option_name', $option_name)->execute();
		} else {
			return $this->db->insertInto('tb_options')->values(['option_value' => $option_value, 'option_name' => $option_name])->execute();
		}
	}
	public function update_dls($last_view, $file_id) {
		$view = $last_view + 1;
		$query = $this->db
				->update('tb_file')
				->set(array('downloads' => $view))
				->where('id', $file_id);
		return $query->execute();
	}
	public function update_last_dls($data) {
		$this->pdo->query('DELETE FROM tb_lastdls
		WHERE id NOT IN (
			SELECT id FROM (
			  SELECT id
			  FROM tb_lastdls
			  ORDER BY download_at DESC
			  LIMIT 5) s
		)')->execute();
		$query = $this->db
				->insertInto('tb_lastdls')
				->values($data);		
				
		return $query->execute();
	}

/*
|--------------------------------------------------------------------------
| DELETE Function
|--------------------------------------------------------------------------
*/
	public function delete_file($file_id) {
		$query = $this->db->deleteFrom('tb_file', $file_id);
		$this->db->deleteFrom('tb_broken', $file_id)->execute();
		return $query->execute();
	}

/*
|--------------------------------------------------------------------------
| Other Function
|--------------------------------------------------------------------------
*/
	public function generateID() {
		$res = '';
		$pattrn = '0123456789abcdefghijklmnopqrstuvwxzyABCDEFGHIJKLMNOPQRSTUVWXZY';
		for ($i=0; $i <= $this->max_file_id; $i++) { 
			$res .= $pattrn[mt_rand(0, strlen($pattrn) - 1)];
		} return $res;
	}
	function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
    }
}

?>