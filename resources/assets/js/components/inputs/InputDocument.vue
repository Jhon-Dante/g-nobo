<template id="template-input-deocument">
    <div class="file-field input-field-component" :style="styles">

        <div class=preview-document>

            <!-- Previsualizacion de la imagen que se obtiene a traves del props -->
            <!-- style="width:500px; height:500px;" frameborder="0" -->
            <!-- <iframe :src="fileview" v-if="preview === '' && fileview !== ''"></iframe> -->
            <iframe :src="_setFile" v-if="preview === '' && file !== ''"></iframe>

            <!-- Previsualizacion de la imagen que se obtiene a traves del methodod: -->
            <!-- style="width:500px; height:500px;" -->
            <iframe :src="preview" v-else-if="preview !== ''" frameborder="0"></iframe>

            

            <!-- Previsualizacion de la imagen que se obtiene a traves del props _previsualizar-->
            <div class="no-document" v-else>
                <i class="material-icons" :class="classFile" style="font-size: 80px;">insert_drive_file</i>
            </div>

        </div>

        <div class="center-add-document" v-if="!onlyPreview">
            <div class="btn btn-primary file">
                <span>
                    <!-- <i class="material-icons" style="float: left">add</i> -->
                    Subir archivo
                </span>
                <input type="file" id="add-file" @change="_previsualizar($event)">
            </div>
        </div>

    </div>
</template>

<style lang="scss" scoped>
    .word {
        color: #166091; 
    }
    .powerpoint {
        color: #f44336;
    }
    .excel {
        color: #4caf50;
    }

    .input-field-component{
        width: 230px;
        margin: auto;

        .center-add-document{
            display: flex;
            justify-content: center
        }

        .preview-document{
            display: flex;
            justify-content: center;
            height: 180px;
            margin-bottom: .9rem;

            .no-document{
                flex: 0 1 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 5rem;
                background: #f2f2f2;
            }

            iframe{
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
        }
    }

</style>


<script>
export default {
    template: "#template-input-deocument",
    name: "inputdeocument",
    props: {
        file: {
            type: String,
            default: ""
        },
        url: {
            type: String,
            default: ""
        },
        video: {
            type: Boolean,
            default: false
        },
        image: {
            type: Boolean,
            default: false
        },
        styles: {
            type: Object,
            default(){
                return {
                    margin: 'auto'
                }
            }
        },
        'type-file': {
            type: Array,
            default(){
                return [];
            }
        },
        'only-preview': {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            fileview: "",
            preview: "",
            type: "",
            isVideo: false,
            isImage: false,
            classFile: '',
            extension: ['docx', 'doc', 'xlsx', 'xls', 'pptx', 'ppt'],
            exe: false
        };
    },
    methods: {

        _previsualizar(evt) {
            let wordType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            let wordType2 = 'application/msword';
            let powerpointType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            let excelType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            let excelType2  = 'application/vnd.ms-excel';
            let googleViewer = 'https://docs.google.com/gview?url=';

            this.preview = "";
            this.file = "";
            this.showZoneRender = true;

            let files = evt.target.files;
        

            for (let i = 0, f; (f = files[i]); i++) {

                let type = "";
                this.$emit("file", {file: files[i], type: type});

                if (f.type == wordType || f.type == wordType2) {
                    this.classFile = 'word';
                    this.fileview = "";
                    return
                } 

                if (f.type == excelType || f.type == excelType2) {
                    this.classFile = 'excel';
                    this.fileview = "";
                    return
                } 

                if(f.type == powerpointType) {
                    this.classFile = 'powerpoint';
                    this.fileview = "";
                    return
                }
                
                let reader = new FileReader();
                reader.onload = (theFile => {
                    return e => {
                        this.preview = e.target.result;
                    };
                })(f);

                reader.readAsDataURL(f);
            }
        },

        _processFile(){
            if(this.file) {
                let extension = this.file.split('.').pop();
                if (this.extension.indexOf(extension) > -1) {
                    this.file = "";

                    if (extension == 'docx' || extension == 'doc') {
                        this.classFile = 'word';
                        this.fileview = "";
                        return
                    } 

                    if (extension == 'xlsx' || extension == 'xls') {
                        this.classFile = 'excel';
                        this.fileview = "";
                        return
                    } 

                    if(extension == 'pptx' || extension == 'ppt') {
                        this.classFile = 'powerpoint';
                        this.fileview = "";
                        return
                    }

                    return
                }
                this.fileview = this.file;
                
                return this.fileview;
            }
        }
    },

    computed: {
        _setFile(){
            return this._processFile();
        }
    },

    watch: {
        file(value){
            return this._processFile();
        }
    },

    mounted(){
        this.isVideo = this.video;
        this.isImage = this.image;
    }
};
</script>
