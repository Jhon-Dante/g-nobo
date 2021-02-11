<template id="template-input-automplete">
    <div class="form-group input-automplete" :id="`automplete${elId}`" style="position: relative">

        <label for="autocomplete-input" :id="`label-autocomplete${elId}`">{{ labeltext }}</label>

        <div class="input-group">
            <div class="form-line">
                <input class="form-control box-autocomplete" :disabled="_disabledInput" readonly="readonly" :id="'box-autocomplete' + elId" type="text" v-model="inputSelect" @focus="$_showList(true, 'open')" @blur="$_showList(false)">
            </div>
        </div>

        <div class="list-group z-depth-1" :class="[display ? 'block' : 'none']" :style="{top: top}">
            <div>
                <input type="text" class="search" :id="'search'+ elId" autocomplete="false" v-model="name" @keyup.40="$_bajar()" @keyup.up="$_subir()" @keydown.enter.prevent="$_enter($event)"  @keyup="searchItemList()" @focus="$_showList(true)" @blur="$_showList(false)">
            </div>
            <ul>
                <li v-if="renderList.length === 0">
                    {{ sinResultado }}
                </li>
                <li v-for="(item,index) in renderList" @click="$_selectItem(item.name, item.id)" class="list-group-item" :key="'list' + index">
                    <span v-html="item.name"></span>
                </li>
            </ul>
        </div>
        
    </div>
</template>

<style scoped>

    .list-group.block{
        display: block;
    }

    .list-group.none{
        display: none;
    }
    .list-group{
        padding: .5rem .9rem;
        position: absolute;
        z-index: 5;
        width: 100%;
        right: 0;
        left: 0;
        margin: auto;
        background-color: #fff;
    }
    .list-group input{
        height: 2.5rem;
    }

    .list-group .list-group-item{
        height: 2.3rem;
        cursor: pointer;
        border-bottom: 1px solid silver;
        display: flex;
        align-items: center;
    }
    
    .list-group-item span {
        padding-left: .5rem;
        text-transform: initial;
    }

    .list-group ul{
        margin: 0;
    }

    .list-group-item:hover span, .list-group-item.active span {
        color: blue
    }
</style>


<script>
export default {
    template: "#template-input-automplete",
    created() {
        // if(this.depent === false){
        //     this.$_getItems();
        // }
    },
    props: {
        urlprincipal: String,
        labeltext: String,
        top: {
            type: String,
            default: '60px'
        },
        elId: {
            type: String,
            default: Math.floor((Math.random() * (100 - 1000) + 100) + 1).toString()
        },
        value: {
            type: String,
            default: ""
        },
        param: {
            type: Object
        },
        depent:{
            type: Boolean,
            default: false
        },
        'sin-resultado': {
            type: String,
            default: "No se encontraron registros."
        },
        savenoresult: {
            type: Boolean,
            default: false
        },
        'set-input': {
            type: Array,
            default(){
                return [];
            }
        },
        disabled: {
            type: Boolean,
            default: false
        },
        'max-length': {
            type: Number,
            default: 15
        }
    },
    data() {
        return {
            itemsList: [],
            name: "",
            display: false,
            selected: {
                pos: 0
            },
            renderList: [],
            inputSelect: "",
            interval: ""
        };
    },
    methods: {
        $_getItems() {

            axios.get(this.urlprincipal)
                .then(res => {
                    this.itemsList = res.data.datos;

                    this.renderList = res.data.datos.slice(0, this.maxLength)
                })
                .catch(err => {});
        },

        $_getItemsDepent() {
            axios.post(this.urlprincipal, this.param)
                .then(res => {
                    this.itemsList = res.data.datos;
                    this.renderList = res.data.datos.slice(0, this.maxLength)
                })
                .catch(err => {});
        },

        $_selectItem(name, id) {
            this.inputSelect = name;
            document.getElementById(`label-autocomplete${this.elId}`).classList.add('active')
            this.$_showList(false, 'close');
            this.$emit('getdata', { id: id, name: name })
        },

        $_showList(bool, extra = "") {

            if(bool){
                clearInterval(this.interval);
                this.selected.pos = 0;
                this.display = bool;

                if(extra == "open"){
                    setTimeout(() => {
                        document.querySelector('#search' + this.elId).focus();
                    }, 100)
                }
                
            }else if(!bool && extra == "close"){
                this.selected.pos = 0;
                this.display = bool;
            }else{
                this.interval = setTimeout(() => {
                    this.display = bool;
                }, 500);
            }
        },

        $_bajar() {            
            if(this.selected.pos <= this.renderList.length){
                this.selected.pos = this.selected.pos + 1;
                this.$_addClassActive();
            }
        },

        $_subir() {
            if(this.selected.pos > 0){
                this.selected.pos = this.selected.pos - 1;            
                this.$_addClassActive();
            }
        },

        $_enter(e) {
            if (this.savenoresult && this.selected.pos === 0) {
                document.getElementById(`label-autocomplete${this.elId}`).classList.add('active')
                this.inputSelect = this.name;
                this.$_showList(false, 'close');
                this.$emit('aggregate', this.inputSelect)
                return
            }
            if (!isNaN(this.selected.pos)) {
                if (this.selected.pos > 0) {                    
                    this.$_selectItem(
                        this.renderList[this.selected.pos -1].name,
                        this.renderList[this.selected.pos - 1].id
                    );
                }
            }
            this.selected.pos = 0;
            this.$_addClassActive();
        },

        searchItemList: function() {
            this.renderList = [];

            if (this.name.length > 0) {
                this.renderList = this.itemsList
                    .filter((item) =>{
                        return item.name.toLowerCase().includes(this.name.toLowerCase())
                    })
                    .slice(0, 10);
            } else {
                this.renderList = this.itemsList.slice(0, 15);
            }
        },

        $_addClassActive(){
            document.querySelectorAll(`#automplete${this.elId} .list-group-item`).forEach(el => {
                el.classList.remove('active');
            });            
            if(this.selected.pos > 0 && this.selected.pos <= this.renderList.length){
                document.querySelector(`#automplete${this.elId} .list-group-item:nth-child(${this.selected.pos})`).classList.add('active');
            }
            
        }
    },
    mounted: function(){
        this.inputSelect = this.value;

        if(this.setInput.length > 0){
            this.itemsList = this.setInput;
            this.renderList = this.setInput.slice(0, this.maxLength)
        }
    },
    watch:{
        value(val){
            this.inputSelect = val;
            console.log(this.inputSelect)
        },
        param(val){
            if(Object.getOwnPropertyNames(val).length > 0){
                this.inputSelect = "";
                this.$_getItemsDepent();
            }
        },
        setInput(setInput){
            this.itemsList = setInput;
            this.renderList = setInput.slice(0, this.maxLength)
        }
    },
    computed: {
        _disabledInput(){
            return this.disabled;
        }
    }
};
</script>
