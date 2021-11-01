<?php 

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
/**
 * 
 */
trait FileTrait
{

    protected $objOriginalFile;
    protected $saveFile;
    protected $diskName = 'public';
    protected $objImg;

    /**
     * Speichert die Datei
     * @param object $objFile
     * @return object $this
     */
    protected function saveFile($objFile)
    {
        $this->objOriginalFile = $objFile;

        $this->saveFile = $this->objOriginalFile->store('',$this->diskName);
        
        return $this;
    }


    /**
     * setzt das Objekt Datiupload $request->file(...) 
     * @param object $objFile
     * @return object $this
     */
    protected function setOriginalFileObject(object $objFile){
        $this->objOriginalFile = $objFile;
        return $this;
    }

    // setDiskPath
    public function getDiskPath()
    {
        return Storage::disk($this->diskName)->path('');

    }

    protected function setImageObject() {
        if( !is_object($this->objImg)) {
            $this->objImg = Image::make($this->objOriginalFile);
        }
    }


    // maxWidth(800,'big_')  //param: breite, string prefix
    public function maxWidth($size, $prefix='')
    {
        $this->setImageObject();
        $this->objImg->resize($size, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $this->objImg->save($this->getDiskPath().$prefix.$this->saveFile);
        return $this;

    }

    /**
     * response Image File
     * @param string $filename
     */
    protected function showFile(string $filename)
    {
        // dd($filename);
        if (Storage::disk($this->diskName)->exists($filename)) {
            $path = Storage::disk($this->diskName)->path($filename);
            // dd($path);
            return response()->file($path);
        }
        abort(404);
    }

    /**
     * 
     * download File
     * @param string $filname
     */

    protected function downloadFile(string $filename)
    {
        if (Storage::disk($this->diskName)->exists($filename)) {
            $path = Storage::disk($this->diskName)->path($filename);
            return response()->download($path);
        }
        abort(404);
    }

    /**
     * 
     * delete File
     * @param string $filname
     */

    protected function deleteFile(string $filename)
    {
        if (Storage::disk($this->diskName)->exists($filename)) {
            Storage::disk($this->diskName)->delete($filename);
        }
        return $this;
    }
}
