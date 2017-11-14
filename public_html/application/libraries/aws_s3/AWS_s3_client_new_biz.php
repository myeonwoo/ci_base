<?php  
// include("../../codeigniter/st/libraries/aws_s3/AWS_s3_client_new_biz.php");

class AWS_s3_client_new_biz {
	private $AWS_S3_ACCESS_KEY      	= null;
	private $AWS_S3_ACCESS_SECRET_KEY 	= null;
	private $AWS_S3_ST_DANGI_BUCKET 	= null;
	
	const AWS_S3_REGION            = 'ap-northeast-2';

	function __construct($config = array()) {
		require_once(BASEPATH.'vendor/autoload.php');
		//bucket 
		$this->AWS_S3_ST_DANGI_BUCKET 	= $config['bucket'];
		$this->AWS_S3_ACCESS_KEY 		= $config['accessKey'];
		$this->AWS_S3_ACCESS_SECRET_KEY = $config['secretKey'];
	}	

	//s3 Client 생성
	private function getClient()
	{
		return Aws\S3\S3Client::factory(array(
			'credentials' => array(
    			'key'    => $this->AWS_S3_ACCESS_KEY,
    			'secret' => $this->AWS_S3_ACCESS_SECRET_KEY,
			),
			'signature' => 'v4',
			'region' => self::AWS_S3_REGION,
		));
	}
	
	 /**
     * uploadFiles : aws s3 파일 업로드 
     *   
     * @param array $files              업로드 할 파일
     * @param string $uploadPath        업로드 할 경로(파일명 포함)
     * 
     * @return array
     *
    */
	public function uploadFiles(array $files, $uploadPath, $contentType = null){
		$s3 = $this->getClient();

		try{

			$objectArray = array(
				'ACL'	 => 'public-read',
				'Bucket' => $this->AWS_S3_ST_DANGI_BUCKET,
				'Key'    => $uploadPath,
				'Body'   => file_get_contents($files['tmp_name']),
			);
			
			if($contentType) $objectArray['ContentType'] = $contentType;
			
			$uploadResult = $s3->putObject($objectArray);
			
			$resultList = array(
				"fileName"		=> basename($uploadPath),
				"byteSize"		=> $files['size'],
				"uploadPath"	=> $uploadPath,
			);

			$result = array(
				"status" 		=> "200",
				"resultMsg" 	=> "SUCCESS",
				"result"		=> $resultList,
			);

		}catch(Exception $e){
			$result = array(
				"status" 		=> "403",
				"resultMsg" 	=> "FAIL",
			);
		}

		return $result;
	}
	
	/**
     * deleteFiles : aws s3 파일 삭제 
     *   
     * @param string $deletePath 삭제할 파일 경로(파일명 포함)        
     *  
     * @return array
     *
     */
	function deleteFiles($deletePath){
		$s3 = $this->getClient();

		try{
			$delete_result = $s3->deleteObject(array(
				'Bucket' 	=> $this->AWS_S3_ST_DANGI_BUCKET,
				'Key'		=> $deletePath,
			));

			$result = array(
				"status" 		=> "200",
				"resultMsg" 	=> "SUCCESS",
			);

		}catch(Exception $e){
			$result = array(
				"status" 		=> "403",
				"resultMsg" 	=> "FAIL",
			);
		}

		return $result;

	}
	
	/**
     * getFiles : aws s3 파일 읽기
     *   
     * @param string $getPath      읽을 파일 경로(파일명 포함)
     *  
     * @return string|null  (업로드 된 파일 읽은 후 tmp에 save한 파일 주소)
     *
     */
	function getFiles($getPath)
	{
		$s3 = $this->getClient();
		//aws sdk file get
		try{
		
			$get_result = $s3->getObject(array(
				'Bucket' 	=> $this->AWS_S3_ST_DANGI_BUCKET,
				'Key'		=> $getPath,
				'SaveAs'	=> '/tmp/'.basename($getPath),
			));

			$file_url = $get_result['Body']->getUri();
			
			return $file_url; 
		
		}catch(Exception $e){
			return null;	
		}
	}

	/**
     * chkFiles : aws s3 파일 존재여부 
     *   
     * @param string $chkPath  	존재여부 확인할 파일의 경로(파일명 포함)
     *  
     * @return bool
     *
    */
	public function chkFiles($chkPath)
	{
		$s3 = $this->getClient();
		//$uploaded_filename = '/bbs_dangicokr/data/123.txt';
		try {
			$get_result = $s3->getObject(array(
				'Bucket' 	=> $this->AWS_S3_ST_DANGI_BUCKET,
				'Key'		=> $chkPath,
			));

			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
     * uploadFiles : aws s3 파일 업로드 
     *   
     * @param string $Binary             업로드 할 파일내용
     * @param string $uploadPath        업로드 할 경로(파일명 포함)
     * 
     * @return array
     *
    */
	public function uploadFilesBinary($binary, $uploadPath){
		$s3 = $this->getClient();

		try{
			$uploadResult = $s3->putObject(array(
				'ACL'	 => 'public-read',
				'Bucket' => $this->AWS_S3_ST_DANGI_BUCKET,
				'Key'    => $uploadPath,
				'Body'   => $binary,
			));
			
			
			
			$resultList = array(
				"fileName"		=> basename($uploadPath),
				"uploadPath"	=> $uploadPath,
			);

			$result = array(
				"status" 		=> "200",
				"resultMsg" 	=> "SUCCESS",
				"result"		=> $resultList,
			);

		}catch(Exception $e){
			$result = array(
				"status" 		=> "403",
				"resultMsg" 	=> "FAIL",
			);
		}

		return $result;
	}
}