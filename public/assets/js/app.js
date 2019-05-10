import Vue from 'vue';
import axios from 'axios';

/**
 * トップページの案件一覧
 * @単発案件
 * @レベニューシェア案件
 * @案件一覧を単発とレベニューシェアで切り替えて表示
 * @案件投稿の金額項目を、案件種別が単発の場合のみ表示する
 */

/**
 * @単発案件
 * Ajax通信でDBの単発案件一覧をJson形式で取得
 * 取得したデータを配列に追加し、v-forで表示
 */
Vue.component('proposition-list-single', {
    data(){
        return{
            list: [],
            
        }
    },
    template:   `<div>
                    <div v-for="item in list" class="c-proposition">
                        <a v-bind:href="getPropositionUrl() + '?proposition_id=' + item.id">
                            <p>{{item.title}}</p>
                            <span class="c-proposition__username">{{item.id}}</span>
                        </a>
                    </div>
                </div>`
    ,
    mounted(){
        axios
            .post('../../fuel/app/classes/model/get-proposition-single.php')
            .then(response => {
                response.data.proposition.forEach( (obj) => {
                    this.list.push(obj);
                });
            })
            .catch(error => console.log(error));
    },
    methods:{
        //案件詳細ページのURL
        getPropositionUrl(){
            return location.origin + '/fuel-vue/public/proposition/';
        }
    }
});
/**
 * @レベニューシェア案件
 * Ajax通信でDBのレベニューシェア案件一覧をJson形式で取得
 * 取得したデータを配列に追加し、v-forで表示
 */
Vue.component('proposition-list-share', {
    data(){
        return{
            list: [],
        }
    },
    template:   `<div>
                    <div v-for="item in list" class="c-proposition">
                        <p>{{item.title}}</p>
                        <span class="c-proposition__username">{{item.type}}</span>
                    </div>
                </div>`
    ,
    mounted: function(){
        axios
            .post('../../fuel/app/classes/model/get-proposition-share.php')
            .then(response => {
                response.data.proposition.forEach( (obj) => {
                    this.list.push(obj);
                });
            })
            .catch(error => console.log(error));
    }
});
/**
 * @案件一覧を単発とレベニューシェアで切り替えて表示
 * タブがクリックされたら$emitでイベント送信
 * View/common/index.php の <proposition-switch-btn>で v-on: を使いイベントを受け取る
 * イベントを受け取ったら switchTab(Single|Share)メソッドを実行し isShow(Single|Share)の真偽値を切り替える
 * isShow(Single|Share)の真偽値に応じて v-if で 単発とレベニューシェアの表示を切り替える
 */
Vue.component('proposition-switch-btn', {
    props: ['type'],
    template:   `<button @click="$emit('event')" class="c-btn">{{type}}</button>`
});

new Vue({
    el: '#proposition-list',
    data:{
        isShowSingle: true,
        isShowShare: false
    },
    methods: {
        switchTabSingle: function(){
            this.isShowSingle = true;
            this.isShowShare = false;
        },
        switchTabShare: function(){
            this.isShowSingle = false;
            this.isShowShare = true;
        }
    }
});

/**
 * @案件投稿の金額項目を、案件種別が単発の場合のみ表示する
 * 案件種別のラジオボタンの要素を取得し、forEachで回してラジオボタンの変更を監視するイベントを設置
 * 金額項目が存在するかを判定　（レベニューシェアを選択、投稿してエラーになった場合金額項目は削除されてるため controller/top.php参照）
 * 金額項目が存在する場合は金額項目の要素を取得(上限と下限)
 * 選択されたラジオボタンが単発かレベニューシェアで　style.display　を切り替える
 */
( () => {
    const nodeListRadio = document.querySelectorAll('.js-change-radio-proposition-type');
    let formPriceLimitExists = (document.getElementById('form_price_limit')) ? true : false;
    let nodePriceLimit = (formPriceLimitExists) ? document.getElementById('form_price_limit').parentNode.parentNode : '';
    let nodePriceLowerLimit = (formPriceLimitExists) ? document.getElementById('form_price_lower_limit').parentNode.parentNode : '';

    nodeListRadio.forEach( inputRadio => {
        inputRadio.addEventListener('change', (e) => {
            if(e.target.value === '0' && formPriceLimitExists){
                nodePriceLimit.style.display = '';
                nodePriceLowerLimit.style.display = '';
            }
            if(e.target.value === '1' && formPriceLimitExists){
                nodePriceLimit.style.display = 'none';
                nodePriceLowerLimit.style.display = 'none';
            }
        }); 
    });
})();

/**
 * @案件詳細ページの編集
 */
( () => {
    document.querySelector('.js-edit-proposition').addEventListener('click', () => {
        axios
        .post('../../fuel/app/classes/model/get-test.php')
        .then(response => {
            console.log(response.data.word);
        })
        .catch(error => console.log(error));
    });
})();

Vue.component('proposition-edit-btn', {
    data(){
        return{

        }
    },
    template: `<button>編集</button>`
    ,
    methods:{
        
    }
});

