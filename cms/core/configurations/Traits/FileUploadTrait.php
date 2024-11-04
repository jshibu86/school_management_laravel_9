<?php

namespace cms\core\configurations\Traits;
use Image;
use Auth;
use cms\core\user\Models\UserModel;
use Illuminate\Support\Facades\Storage;
use Session;
use File;
trait FileUploadTrait
{
    public function uploadImage($image, $type = null)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(256, 256)
            ->save("school/profiles/" . $make_name);
        $uploadPath = asset("/school/profiles/") . "/" . $make_name;
        // dd($uploadPath);
        return $uploadPath;
    }
    public function uploadUserImage($image, $type = null)
    {
        $directory = public_path("school/users/");

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(256, 256)
            ->save("school/users/" . $make_name);
        $uploadPath = asset("/school/users/") . "/" . $make_name;

        return $uploadPath;
    }

    public function CoverImage($image, $path)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(1920, 1080)
            ->save($path . $make_name);
        $uploadPath = "/" . $path . $make_name;

        return $uploadPath;
    }
    public function ProductImage($image, $path)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(200, 200)
            ->save($path . $make_name);
        $uploadPath = "/" . $path . $make_name;

        return $uploadPath;
    }
    public function uploadAttachment($image, $type = null, $path)
    {
        $extensions = ["jpeg", "png", "gif", "svg", "jpg"];

        $type = $image->getClientOriginalExtension();

        // dd($type);

        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();

        if (in_array($type, $extensions)) {
            Image::make($image)->save($path . $make_name);
        } else {
            $image->move($path, $make_name);
        }

        $uploadPath = "/" . $path . $make_name;

        //  dd($uploadPath);

        return $uploadPath;
    }
    public function uploadFile($file, $type = null)
    {
        $make_name =
            hexdec(uniqid()) . "." . $file->getClientOriginalExtension();
        $file->move("files/certificates", $make_name);
        $uploadPath = "/files/certificates/" . $make_name;

        return $uploadPath;
    }
    public function deleteImage($path_to_image_directory = "/", $image = "")
    {
        if ($image) {
            $fname = public_path() . $image;

            if ($this->fileexistcheck($fname)) {
                unlink($fname);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function fileexistcheck($filename)
    {
        if (File::exists($filename)) {
            return true;
        } else {
            return false;
        }
    }

    public function storeSaptieMedia($user, $image)
    {
        $mediaId = $user
            ->addMedia($image)
            ->toMediaCollection(
                UserModel::COLLECTION_PROFILE_PICTURES,
                config("app.media_disc")
            )->id;

        return $mediaId;
    }

    public function GmailGroupImage($image, $type = null)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(256, 256)
            ->save("gmailgroupimage/" . $make_name);
        $uploadPath = "/gmailgroupimage/" . $make_name;

        return $uploadPath;
    }

    public function GmailGroupMessageFiles($images, $type = null)
    {
        // dd($images);
        $paths = [];
        foreach ($images as $image) {
            // Generate a unique file name
            $make_name =
                hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
            $image->move("gmailgroupimage/groupmessagefiles", $make_name);

            $uploadPath = "/gmailgroupimage/groupmessagefiles/" . $make_name;

            $paths[] = $uploadPath;
        }

        return $paths;
    }

    public function GmailIndividualMessageFiles($images, $type = null)
    {
        // dd($images);
        $paths = [];
        foreach ($images as $image) {
            // Generate a unique file name
            $make_name =
                hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
            $image->move("gmailgroupimage/individualmessagefiles", $make_name);

            $uploadPath =
                "/gmailgroupimage/individualmessagefiles/" . $make_name;
            $paths[] = $uploadPath;
        }
        return $paths;
    }

    public function cmsuploadImage($image, $type = null)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(256, 256)
            ->save("cmsmenu/home/" . $make_name);
        $uploadPath = "/cmsmenu/home/" . $make_name;

        return $uploadPath;
    }

    public function uploadCMSMenuImage($image, $path)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)->save($path . $make_name);
        $uploadPath = $path . $make_name;

        return $uploadPath;
    }
}
