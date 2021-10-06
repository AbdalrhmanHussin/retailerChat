<template>
    <div class="model-overlay w-100 position-fixed w-100">
        <transition name="model" appear>
            <div class="modal avatar" tabindex="-1" role="dialog" v-if="this.$store.state.model == 'avatar'">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title color-sv fs-14">Change Avatar</h5>
                        </div>
                        <div class="modal-body">
                            <file-pond
                                name="avatar"
                                ref="pond"
                                label-idle="Modify your avatar"
                                :allow-multiple="false"
                                :allowFileTypeValidation="true"
                                :acceptedFileTypes="['image/png', 'image/jpeg']"
                                :allowImageValidateSize="true"
                                :imageValidateSizeMaxWidth="400"
                                :server="serverData"
                                accepted-file-types="image/jpeg, image/png"
                                v-on:init="handleFilePondInit"
                                v-on:processfiles="uploaded(e)"
                            />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="this.$store.state.model_overlay = false">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import { mapGetters } from 'vuex';

// Create component
const FilePond = vueFilePond(FilePondPluginFileValidateType,FilePondPluginImageResize);

export default {
    data: function () {
        return { 
            serverData: {
                url: '/user/update',
                data: {
                    name: 'name'
                },
               
                process: {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': localStorage.getItem('csrf')
                    },
                    ondata: (formData) => {
                        formData.append('name', this.name); 
                        formData.append('update','image'); 
                        return formData;
                    },
                    withCredentials: false
                }
            },

        };
    },
    computed: {
        ...mapGetters([
            'getUser'
        ]),

        name() {
            return this.getUser.id + Date.now();
        }
    },
    methods: {
        handleFilePondInit: function () {
            console.log("FilePond has initialized");
        },
        uploaded() {
           this.$store.state.model_overlay = false;
           this.$store.state.model = '';
           this.$store.state.user.image = '';
           this.$store.state.user.image = this.name + '.jpg';
        }
    },
    components: {
        FilePond,
    },
};
</script>
