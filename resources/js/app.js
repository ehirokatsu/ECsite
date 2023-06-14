import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/*
console.log('test');
  // 郵便番号が入力された時のイベントハンドラを設定
  document.getElementById('postalCode').addEventListener('change', function() {
    var postalCode = this.value;

    // 郵便番号をAPIに送信して、他の住所情報を取得する
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + postalCode + '&callback=getAddNameByZipcloudAPI', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        console.log(response);
        // APIからのレスポンスを利用して、他の住所情報を入力する
        document.getElementById('address_1').value = response.address1;
        document.getElementById('address_2').value = response.address2;
        document.getElementById('address_3').value = response.address3;
      } else {
        console.error('Request failed. Status: ' + xhr.status);
      }
    };
    xhr.onerror = function() {
      console.error('Request failed');
    };
    xhr.send();
  });



  document.getElementById('postalCode').addEventListener('change', function() {
    var postalCode = this.value;
    
    // 郵便番号をAPIに送信して、他の住所情報を取得する
    var url = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + postalCode + '&callback=getAddNameByZipcloudAPI';
    var script = document.createElement('script');
    script.src = url;
    document.body.appendChild(script);
  });

  // APIからのレスポンスを受け取るコールバック関数
  function getAddNameByZipcloudAPI(response) {
    if (response.results && response.results.length > 0) {
      var addressData = response.results[0];

      // 他の住所情報を入力する
      document.getElementById('address_1').value = addressData.address1;
      document.getElementById('address_2').value = addressData.address2;
      document.getElementById('address_3').value = addressData.address3;
    }
  }

  (async () => {
    try {
        console.log('start');
      const response = await fetch('https://zipcloud.ibsnet.co.jp/api/search?zipcode=2120033&callback=getAddNameByZipcloudAPI');
      if (!response.ok) {
        throw new Error('Network response was not ok');  // fetchが成功したかどうかの判定
      }
      console.log(response);
      const data = await response.json();
      console.log(data);
    } catch(e) {
      alert(e);  // 例外（エラー）が発生した場合に実行
    } finally {
      console.log('finally');  // 処理結果の成否に関わらず実行
    }
})();

*/