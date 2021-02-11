<template id="template-banner-create">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Banners</h1>
            </div>
        </div>
        <!-- <div class="col s12"> -->
        <card-main class="row" style="min-height: 600px">
            <card-content>

                <div class="row">
                    <a href="#!" class="col s12 container-btn-add" @click="_addImg">
                        <button class="btn-add">
                            <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                        </button>
                        <div class="btn-add-text">
                            Agregar nuevo
                        </div>       
                    </a>
                </div>

                <form action="" @submit.prevent="_store"> 
                    <template v-for="(img, i) in bufferImg">
                        <div class="col s12 m3 items__file" :key="'banner-' + i">
                            <input-file accept="image/jpg, image/png, image/jpeg" :btn="false" :file="img.img != '' ? `${urlBase + 'img/slider/' + img.img}` : ''" :image="true" v-on:file="_setFile(img, i, $event)"></input-file>
                            <a href="#!" class="file__claer" @click="_sliceItem(img.id, i)">x</a>
                        </div>
                    </template>

                    <div class="hide">
                        <img :src="file" id="file-valid-size">
                    </div>
                </form>

                <byte-modal v-on:pressok="_delete" :confirm="true">
                    <template>
                        <div class="container-confirmation">
                            <div class="confimation__icon">
                                <i class="material-icons">error_outline</i>
                            </div>
                            <div class="confirmation__text">
                                <h5>¿ Realmente deseas <b>Eliminar</b> esta Banner ?</h5>
                            </div>
                        </div>
                    </template>
                </byte-modal>
            </card-content>
        </card-main>
    </section>
    <!-- </div> -->
</template>

<style lang="scss" scoped>
    .card{
        margin: 0.5rem .5rem 2rem .5rem;
    }
</style>


<script>
export default {
    template: "#template-blog-create",
    
    props: {
        banners: {
            type: Array,
            default: []
        }
    },

    data() {
        return {
            bufferImg: [],
            url: urlBase,
            urlBase: urlBase,
            file: "",
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: "view"
                },
                data: {}
            },
            valid: {
                width: 0,
                height: 0
            }
        }
    },
    methods: {
        _addImg(){
            this.bufferImg.push({
                id: 0,
                img: ''
            });
        },

        _setFile(img, i, file) {
            if(!file.file.type) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            if(!file.file.type.match("image/*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            this._validDimension(file.file);
            setTimeout(function(){
              console.clear();
            }, 10);
            setTimeout(() => {
                if(this.valid.width > 1300 || this.valid.height > 500){
                    let temp = this.bufferImg;
                    this.bufferImg = [];
                    
                    setTimeout(() => {
                        temp.forEach(el => {
                            this.bufferImg.push(el);
                        });
                    }, 200)

                    swal("", "Disculpe, las dimensiones de la imagen es incorrecta. Las dimensiones deben ser de 1300 x 500", "error");
                    return
                }

                showLoading();
                let formData = new FormData(),
                    id = this.bufferImg[i].id;

                formData.append('file', file.file);
                formData.append('id', id);
                
                axios.post("admin/banners/upload", formData)
                    .then(res => {
                        quiLoading();
                        if(id == 0)
                            this.bufferImg[i].id = res.data.id;
                        swal("", "Banner registrado con éxito", "success");
                    })
                    .catch(err => {
                        let message = "Disculpe, ha ocurrido un error";

                        if(err.response.status == 422){
                            message = err.response.data.error;
                            
                            let temp = this.bufferImg;
                            this.bufferImg = [];

                            setTimeout(() => {
                                this.bufferImg = temp;
                            }, 200);
                        }

                        quiLoading();

                        swal("", message, "error");
                    });
            }, 500)
        },

        _sliceItem(file, i){
            if(file == 0) {
                this.bufferImg.splice(i, 1);
                return
            }
            this.modal.data = {
                file: file,
                index: i
            };
            this.modal.init.open();
        },

        _delete(){
            this.modal.init.close();
           
            showLoading();
            axios.post("admin/banners/delete", {id: this.modal.data.file})
                .then(res => {
                    quiLoading();
                    this.bufferImg.splice(this.modal.data.index, 1);
                    swal("", "Banner eliminado con éxito", "success");
                })
                .catch(err => {
                    quiLoading();
                    swal("", "Disculpe, ha ocurrido un error", "error");
                });
        },

        _validDimension(file){
            let fr = new FileReader;
    
            fr.onload = () => {
                let img = new Image;
                
                img.onload = () => {
                    this.valid.width = img.width;
                    this.valid.height = img.height;
                };
                
                img.src = fr.result;
            };
            
            fr.readAsDataURL(file);
        }
    },

    created(){

        if(this.banners.length > 0){
            this.banners.forEach(el => {
                this.bufferImg.push({
                    id: el.id,
                    img: el.foto
                })
            });
        }else{
            this.bufferImg.push({
                id: 0,
                img: ''
            });
        }
    },

    mounted(){
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
    }
}
</script>