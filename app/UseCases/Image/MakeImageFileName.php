<?php

namespace App\UseCases\Image;

use Carbon\Carbon;

class MakeImageFileName
{
    public function __invoke(String $imageName): String
    {
        //現在日時を取得
        $now = Carbon::now()->format('Y_m_d_H_i_s');

        //商品画像を保存する時のファイル名を作成する。ファイル名の衝突対策でランダム文字列を付加する
        return $now . '_' . \Str::random(5) . '_' . $imageName;
    }
}
