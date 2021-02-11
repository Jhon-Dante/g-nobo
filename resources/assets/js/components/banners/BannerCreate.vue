<template id="template-blog-create">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Crear Blog</h1>
            </div>
        </div>
    <!-- <div class="col s12"> -->
        <div class="row">
            <div class="col s12">
                <a :href="url+'admin/blogs'" class="btn btn-back">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label for=""> Volver</label>
                    </div>
                </a>
            </div>
        </div>
        <card-main class="row">
            <card-content>
                <form action="" @submit.prevent="_store">
                    <div class="col s12 m6 center-align">
                        <label for="title" class="label-impegno">Título (Español)</label>
                        <input type="text" name="title" id="title" v-model="form.title" class="browser-default input-impegno">
                    </div>

                    <div class="col s12 m6 center-align">
                        <label for="title_english" class="label-impegno">Título (Ingles)</label>
                        <input type="text" name="title_english" id="title_english" v-model="form.title_english" class="browser-default input-impegno">
                    </div>
                    
                    <div class="col s12 m6">
                        <label for="title_english" class="label-impegno">Título (Ingles)</label>
                        <input-file :btn="false" :image="true" v-on:file="_setFile"></input-file>
                    </div>

                    <div class="col s12 m6">
                        <input-file :btn="false" :image="true" v-on:file="_setFileEnglish"></input-file>
                    </div>

                    <div class="col s12 center-align content-image">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </card-content>
        </card-main>
    </section>
    <!-- </div> -->
</template>

<style lang="scss">

</style>


<script>
export default {
    template: "#template-blog-create",
    
    data() {
        return {
            form: {
                title: "",
                title_english: "",
                img: "",
                img_english: ""
            },
            url: urlBase
        }
    },
    methods: {
        _setFile(file) {
            if(file.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }
            this.form.img = file.file         
        },

        _setFileEnglish(file){
            this.form.img_english = file.file
        },

        _store(){
            let formData = new FormData(); 
            formData.append("title",this.form.title);
            formData.append("title_english",this.form.title_english);
            formData.append("img",this.form.img);
            formData.append("img_english", this.form.img_english);

            axios.post("admin/blogs",formData)
                .then(resp=>{
                    swal({
                        title: '',
                        text: 'Se ha creado el blog con éxito',
                        timer: 2000,
                        showConfirmButton: false,
                        type: 'success'
                    },()=>{
                        window.location = urlBase+"admin/banners";
                    });
                }).catch(err=>{
                    let message = "Disculpe, ha ocurrido un error";
                    if (err.response.status == 422){
                        message = err.response.data.error;
                    }
                    swal("", message, "error");
                })
        }
    },

    mounted(){

    }
}
</script>

