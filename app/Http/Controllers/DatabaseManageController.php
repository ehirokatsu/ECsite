<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class DatabaseManageController extends Controller
{
    public function index()
    {
        //

        return view('databaseManage.index');
    }

    public function export(Request $request)
    {
        //
        $databaseName = $request->database;
        //dump($databaseName);


        //ストリームを書き込みモードで開く
        $stream = fopen('php://temp', 'w');

        //CSVファイルのカラム（列）名の指定
        $columnsName = [
            'id',
            'name',
            'cost',
            'image',
        ];    

        //1行目にカラム（列）名のみを書き込む（繰り返し処理には入れない）
        fputcsv($stream, $columnsName);

        //書き込みたいデータを取得
        $people = DB::table('products')->select(['id', 'name', 'cost', 'image'])->get();

        //DBのデータを書き込む
        foreach ($people as $person) {

            //1行読み込む
            $csv = [
                $person->id,
                $person->name,
                $person->cost,
                $person->image,
            ];
            //1行分をストリームに書く
            fputcsv($stream, $csv);
        }
        
        //fputcsvで書き込まれた後のファイルポインタはファイルの終端になっているため
        //ファイルポインタを先頭に戻す
        rewind($stream);

        //ストリームの最初からデータを取得する
        $csv = stream_get_contents($stream);

        //$csvの文字エンコードをUTF-8からsjis-winに変更
        $csv = mb_convert_encoding($csv, 'sjis-win', 'UTF-8');

        //ストリームを閉じる
        fclose($stream);                      
        
        //ヘッダー情報を指定する（ダウンロード用の設定）
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=test.csv'
        );
        
        //HTTPレスポンスを生成
        //第1引数：コンテンツ
        //第2引数：レスポンスステータス
        //第3引数：レスポンスヘッダー
        return Response::make($csv, 200, $headers);


        //return redirect('databaseManage');
    }
}
