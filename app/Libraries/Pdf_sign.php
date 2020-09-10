<?php

/**
 *  Implementasi tanda tangan elektronik pada PHP dengan spesifikasi :
 *  Modul    : Command Line Interface (Cli) OpenSSL
 *  Keystore : PKCS12 File
 *
 * Useful resources are as follows:
 *
 *  - {@link http://jsignpdf.sourceforge.net/ JSignPdf Web}
 *
 * Here's an example of how to use this library:
 * <code>
 * <?php
 *
 * require_once('../src/pdf-sign-cli.php');
 * $pathPDF = 'pdf/b.pdf';
 * $pdfSigner =  new BSrE_PDF_Signer_Cli($pathPDF);
 * $pdfSigner->readCertificateFromFile(
 *   'cert/maret_2.p12',
 *   '1234.!'            
 * );
 * $pdfSigner->setAppearance(
 *    $position = array(
 *        'llx' => '442',
 *        'lly' => '831',
 *        'urx' => '577',
 *        'ury' => '774'
 *    ),
 *    $page = 1,
 *    $spesimen = null
 * );
 * $pdfSigner->setTimeStamp('http://TSA_URL','TSA_UserName','TSA_Password');
 * $pdfSigner->setOCSP('http://OCSP_URL');
 * $pdfSigner->sign();
 * ?>
 * </code>
 *
 *
 * @category  PDFSign
 * @package   BSrE_PDF_Signer_Cli
 * @author    Egi Anggriawan <egi.anggriawan@bssn.go.id>
 */
namespace App\Libraries;
class Pdf_sign 
{
    /**
     * Passphrase untuk ekstrak nilai private key dalam P12
     * 
     * @var string
     */
    private $passphrase;

    /**
     * 
     * @var string
     * @access private
     */
    private $pkcs12Path;

    /**
     * 
     * @var string
     * @access private
     */
    private $fileName;

    /**
     * 
     * @var string
     * @access private
     */
    private $fileOutputName;

    /**
     * 
     * @var string
     * @access private
     */
    private $directoryName;

    /**
     * @var string
     * @access private
     */
    private $suffixName = '_signed';

    /**
     * @var string
     * @access private
     */
    private $error;

    /**
     * @var string
     * @access private 
     */
    private $dirLib;

    /**
     * @var string
     * @access private 
     */
    private $library = '/JSignPDF/JSignPdf.jar';

    /**
     * @var string
     * @access private 
     */
    private $addCmd = '';

    /**
     * 
     * 
     */
    public function addmultipleSign()
    {
        $this->addCmd .= ' -a ';
    }


    public function clearLogFile()
    {
        $path = $this->dirLib . '/JSignPDF/log/error.log';

        try{
            $tmp_f = fopen($path ,'a+');
            fwrite($tmp_f,'');
            fclose($tmp_f);	
        }catch(Exception $e){
            $this->error = 'File Log tidak dapat dibaca';
            return;
        }
        
    }

    public function __construct(){
        $this->dirLib = dirname(dirname(__FILE__)) . '/Libraries';
    }

    /**
     * 
     * @access public 
     */
    function getError()
    {
       return $this->error;
    }

    public function logError($message)
    {    
        $path = $this->dirLib . '/JSignPDF/log/error.log';

        $writeLog = 'Waktu : ' . date("Y-m-d h:i:s a") . PHP_EOL 
                  . 'Dokumen : ' . $this->fileName . PHP_EOL
                  . 'Pesan Error : '.PHP_EOL ;

        if(is_array($message)){
            foreach ($message as $m) {
                $writeLog .= $m . PHP_EOL;
            }
        }
        
        $writeLog .= '======================================'
                   . '======================================'
                   . '======================================' . PHP_EOL;

		try{
            $tmp_f = fopen($path ,'a+');
            fwrite($tmp_f, $writeLog);
            fclose($tmp_f);	
        }catch(Exception $e){
            $this->error = 'File Log tidak dapat dibaca';
            return;
        }
    }

    public function readCertificateFromFile($pkcs12Path, $passphrase)
    {
        $pkcs12File = @file_get_contents($pkcs12Path);

        if(false === $pkcs12File){
            $this->error = 'File Sertifikat tidak ditemukan';
            return;
        }

        $p12Read = openssl_pkcs12_read(
            $pkcs12File,
            $pkcs12,
            $passphrase
        );

        if (false === $p12Read) {
            $this->error = 'Passphrase Salah';
            return;
        }
        
        $this->pkcs12Path = $pkcs12Path;
            
        $this->passphrase = $passphrase;
        
    } 

    public function setCertificationLevel($certLevel = 0)
    {
        switch ($certLevel) {
            case 0:
                $this->addCmd .= ' -cl NOT_CERTIFIED' ; break;
            case 1:
                $this->addCmd .= ' -cl CERTIFIED_NO_CHANGES_ALLOWED' ; break;
            case 2:
                $this->addCmd .= ' -cl CERTIFIED_FORM_FILLING' ; break;
            case 3:
                $this->addCmd .= ' -cl CERTIFIED_FORM_FILLING_AND_ANNOTATIONS' ; break;
            default:
                $this->addCmd .= ' -cl NOT_CERTIFIED' ; break;
        }
    }

    public function setContact($contact)
    {
        $contact = preg_replace('/[^\w]/', '', $contact);
        $this->addCmd .= ' --contact "' . $contact .'"';
    }

    private function GetBasePath() { 
        $projectName = explode('/',$_SERVER['PHP_SELF'])[1];
        $basePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $projectName;
        return $basePath; 
    } 

    public function setDirectory($dir, $create = true)
    {   
        $basePath = "";//$this->GetBasePath();
        
        if(! is_dir($dir) && $create){
            if(!@mkdir($dir)){
                $this->error = 'Gagal membuat direktori';
                return;
            }
        }elseif (! is_dir($dir) && !$create){
            $this->error = 'Nama Direkrori '. $dir.' tidak ditemukan';
            return;
        }
        // atur ulang nama direktori
        $this->directoryName = $dir;
    }

    public function setDocument($pdfs)
    {
        if(is_array($pdfs)){
            $this->fileName = implode(" ", $pdfs);
            $this->directoryName = dirname($pdfs[0]);
        }
        else{
            $this->fileName = $pdfs;
            $this->directoryName = dirname($pdfs);
            if(@file_get_contents($pdfs) === false){
                $this->error = 'Dokumen PDF tidak bisa di buka';
            }
        }
    }

    public function setLibraryPath($pathLibrary)
    {
         $this->dirLib = $pathLibrary;
    }

    public function setReason($reason){
        $reason = preg_replace('/[^\w]/', '', $reason);
        $this->addCmd .= ' -r ' . $reason;
    }

    public function setLocation($location)
    {
        $location = preg_replace('/[^\w]/', '', $location);
        $this->addCmd .= ' -l ' . $location;
    }

    public function setTimeOfSigning()
    {
        //next
    }

    public function setTimeStamp($tsa_url, $tsa_uname, $tsa_pass)
    {
        $this->addCmd .= ' -ts '.$tsa_url.' -ta PASSWORD -tsu "' . $tsa_uname . '" -tsp "' . $tsa_pass . '" ';
    }

    public function setOCSP($ocsp_url)
    {
        $this->addCmd .= ' --ocsp --ocsp-server-url '.$ocsp_url . ' ';
    }

    public function setSuffixFileName($suffix)
    {
        $this->suffixName = $suffix;
    }

    public function setAppearance(
        $position = array(
            'llx' => '442',
            'lly' => '831',
            'urx' => '577',
            'ury' => '774'
        ),
        $page = 1,
        $spesimen = null)
    {
        $this->addCmd .= ' -V --bg-scale 0 --l4-text "" --l2-text "" -pg ' . $page 
                                . ' -llx ' . $position['llx'] . ' -lly ' . $position['lly'] . ' -urx ' . $position['urx'] . ' -ury ' . $position['ury'] . ' ';

        if(!is_null($spesimen))
            $this->addCmd .= '--bg-path "' . $spesimen . '" ';

    }

    public function setPermission($userPassword = '', $encrypt = false, $disAllowPrint = false)
    {
        $userPassword = preg_replace('/[^\w]/', '', $userPassword);
        if($encrypt)
            $this->addCmd .= ' -pe PASSWORD -opwd owner -upwd ' . $userPassword ;
        if($disAllowPrint)
            $this->addCmd .= ' -pr DISALLOW_PRINTING';
    }

    public function sign()
    {
        if(!is_null($this->error)) return false;

        $command = 'java -jar ' . $this->dirLib . $this->library . ' ' . $this->fileName . ' -tsh SHA256 -ha SHA256 ' . '-kst PKCS12 -ksf "' . $this->pkcs12Path
                    . '" -ksp "' . $this->passphrase . '" -d "' . $this->directoryName . '" -os "' . $this->suffixName . '" ';
         echo $command;
        // exit();
        exec($command, $val, $err);
        

        if($err == 0 || $err == 3) return true;
        else{
            $this->logError($val);
            $this->error = 'Proses Tanda Tangan Elektronik gagal';
            return false;
        }
        
    }
    
}
