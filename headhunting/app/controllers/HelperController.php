<?php
/**
 * HelperController.php
 *
 * This file contatins controller class to User Common Helper Function.
 *
 * @category   Class
 * @package    Helper
 * @version    SVN: <svn_id>
 * @since      29th Feb 2016
 */
 
/**
 * Contrller class to search APIS
 *
 * @package    Helper
 * @category   Class
 *
 */
class HelperController extends BaseController {

    /**
     * Setup the Responce for JSON/JSONP
     *
     * @param Array $data : Data to be passed should be in Array Format
     *
     * @return Object JSON
     */
    public function sendJsonResponse($data) {

        // Setting Access Control to *
        header('Access-Control-Allow-Origin: *');

        // Setting Content Type to JSON
        header("content-type:application/json");

        if (isset($data['error']) && $data['error']) {

            // Return Object Json
            return Response::json($data, 200)
                             ->setCallback(Input::get('callback'));

        // Checking if Data Exists Or not
        } else if (count($data) >= 1) {

            // Return Object Json
            return Response::json(
                    array(
                        'error' => false,
                        'data' => $data,
                    ), 200
            )->setCallback(Input::get('callback'));
        } else {

            // Return Empty Object Message
            return Response::json(
                    array(
                        'error' => false,
                        'data' => trans('messages.no_data'),
                    ), 200
            )->setCallback(Input::get('callback'));
        }
    }

    /**
     * sendJsonResponseOnly : Setup the Responce for JSON/JSONP.
     *
     * @param Array $data : Data to be passed should be in Array Format
     *
     * @return Object JSON
     *
     */
    public function sendJsonResponseOnly($data) {

        // Setting Access Control to *
        header('Access-Control-Allow-Origin: *');

        // Setting Content Type to JSON
        header("content-type:application/json");

        return Response::json($data, 200)
                       ->setCallback(Input::get('callback'));
    }

    public function read_doc($fileName) {
	    $line_array = array();
	    $fileHandle = fopen( $fileName, "r" );
	    $line       = @fread( $fileHandle, filesize($fileName) );
	    $lines      = explode( chr( 0x0D ), $line );
	    $outtext    = "";
	    foreach ( $lines as $thisline ) {
	        $pos = strpos( $thisline, chr( 0x00 ) );
	        if (  $pos !== false )  {

	        } else {
	            $line_array[] = preg_replace( "/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $thisline );

	        }
	    }

	    return implode("\n",$line_array);
	}

    /**
     * smart_resize_image :  image resize function
     * @param  $file - file name to resize
     * @param  $string - The image data, as a string
     * @param  $width - new image width
     * @param  $height - new image height
     * @param  $proportional - keep image proportional, default is no
     * @param  $output - name of the new file (include path if needed)
     * @param  $delete_original - if true the original image will be deleted
     * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
     * @param  $quality - enter 1-100 (100 is best quality) default is 100
     *
     * @return boolean|resource
     */
    public function smart_resize_image($file,
            $string             = null,
            $width              = 0,
            $height             = 0,
            $proportional       = false,
            $output             = 'file',
            $delete_original    = true,
            $use_linux_commands = false,
            $quality = 100
    ) {

        // assigning Extra Memory for Memory Excess Issue.
        ini_set ( "memory_limit", "350M" );

        if ($height <= 0 && $width <= 0) {

            return false;
        }
        if ($file === null && $string === null) {

            return false;
        }

        // Setting defaults and meta
        $info = $file !== null ? getimagesize ( $file ) : getimagesizefromstring ( $string );
        $image = '';
        $final_width = 0;
        $final_height = 0;
        list ( $width_old, $height_old ) = $info;
        $cropHeight = $cropWidth = 0;

        // Calculating proportionality
        if ($proportional) {

            if ($width == 0) {

                $factor = $height / $height_old;
            } elseif ($height == 0) {

                $factor = $width / $width_old;
            } else {

                $factor = min ( $width / $width_old, $height / $height_old );
            }

            $final_width = round ( $width_old * $factor );
            $final_height = round ( $height_old * $factor );
        } else {

            $final_width = ($width <= 0) ? $width_old : $width;
            $final_height = ($height <= 0) ? $height_old : $height;
            $widthX = $width_old / $width;
            $heightX = $height_old / $height;
            $x = min ( $widthX, $heightX );
            $cropWidth = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
        }

        // Loading image to memory according to type
        switch ($info [2]) {

            case IMAGETYPE_JPEG :
                $file !== null ? $image = @imagecreatefromjpeg ( $file ) : $image = imagecreatefromstring ( $string );
                break;
            case IMAGETYPE_GIF :
                $file !== null ? $image = @imagecreatefromgif ( $file ) : $image = imagecreatefromstring ( $string );
                break;
            case IMAGETYPE_PNG :
                $file !== null ? $image = @imagecreatefrompng ( $file ) : $image = imagecreatefromstring ( $string );
                break;
            default :
                return false;
        }

        // This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor ( $final_width, $final_height );

        if (($info [2] == IMAGETYPE_GIF) || ($info [2] == IMAGETYPE_PNG)) {

            $transparency = imagecolortransparent ( $image );
            $palletsize = imagecolorstotal ( $image );

            if ($transparency >= 0 && $transparency < $palletsize) {

                $transparent_color = @imagecolorsforindex ( $image, $transparency );
                $transparency = imagecolorallocate ( $image_resized, $transparent_color ['red'], $transparent_color ['green'], $transparent_color ['blue'] );
                imagefill ( $image_resized, 0, 0, $transparency );
                imagecolortransparent ( $image_resized, $transparency );
            } elseif ($info [2] == IMAGETYPE_PNG) {

                imagealphablending ( $image_resized, false );
                $color = imagecolorallocatealpha ( $image_resized, 0, 0, 0, 127 );
                imagefill ( $image_resized, 0, 0, $color );
                imagesavealpha ( $image_resized, true );
            }
        }

        // Resizing Image
        imagecopyresampled ( $image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight );

        // Taking care of original, if needed
        if ($delete_original) {

            if ($use_linux_commands) {

                exec ( 'rm ' . $file );
            } else {

                @unlink ( $file );
            }
        }

        // Preparing a method of providing result
        switch (strtolower ( $output )) {

            case 'browser' :
                $mime = image_type_to_mime_type ( $info [2] );
                header ( "Content-type: $mime" );
                $output = NULL;
                break;
            case 'file' :
                $output = $file;
                break;
            case 'return' :
                return $image_resized;
                break;
            default :
                break;
        }

        // Writing image according to type to the output destination and image quality
        switch ($info [2]) {

            case IMAGETYPE_GIF :
                imagegif ( $image_resized, $output );
                break;
            case IMAGETYPE_JPEG :
                imagejpeg ( $image_resized, $output, $quality );
                break;
            case IMAGETYPE_PNG :
                $quality = 9 - ( int ) ((0.9 * $quality) / 10.0);
                imagepng ( $image_resized, $output, $quality );
                break;
            default :
                return false;
        }

        // back to normal
        ini_set ( "memory_limit", "32M" );
        return true;
    }

    /**
     * httpPost() : Http Post Request Using CURL
     *
     * @param String $url : Url to hit
     * @param Array $params : Parameters to be passed.
     *
     * @return Output Curl
     */
    function httpPost($url,$params) {

        $postData = '';

        // Create name value pairs seperated
        foreach ($params as $k => $v) {

            $postData .= $k . '='.$v.'&';
        }

        rtrim($postData, '&');

        // Curl Initialization
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, count($postData));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

        // Curl Request
        $output=curl_exec($curl);

        if (!$output) {

            Log::info(json_encode(curl_getinfo( $curl )));
            Log::info(json_encode(curl_errno( $curl )));
            Log::info(json_encode(curl_error( $curl )));
        }
        curl_close($curl);
        return $output;
    }

    /**
     * sendMail : for sending mail to the Users.
     *
     * @param ARRAY $data: Data to send to template,
     * @param STRING $template : Template for Sending Mail to be called.
     * @param User Object $user : To whome mail to send.
     * @param String $subject : Subject for the Mail
     */
    public function sendMail($data, $template, $user, $subject) {

    	// use Mail::send function to send email passing the data and using the $user variable in the closure
    	try {

    		Mail::send($template, $data, function($message) use ($user) {

    			$message->to($user->email, $user->username)->subject($subject);
    		});
    	}
    	catch(\Exception $e) {

    		Log::info('Mail error! '.$subject. $e->getCode());
    	}
    }
}
