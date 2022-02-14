<template>
    <div>
        <div v-if="showForm == 'true'">
            <div class="form-group" :class="{'has-error': hasError }">
                <label class="control-label">{{ _t('comment-form-label') }}*</label>
                <comment-editor :input="commentInput" :name="'commentInput'"></comment-editor>
            </div>
            <div class="form-group">
                <div class="pull-left">
                    <button class="btn btn-success" @click.prevent="saveComment">{{ _t('Send') }}</button>
                </div>
            </div>
        </div>
        <div class="box-group form-group" id="accordion">
            <div class="panel box bg-success" v-for="(comment, index) in comments">
                <div class="box-header">
                    <h5>
                        <a data-toggle="collapse" :href="'#message_' + comment.id">
                            {{ comment.creator_name }} {{ comment.creator_surname }}
                        </a>
                        <span class="mailbox-read-time pull-right">{{ _t('Sent at') }} {{ comment.created_at | dateFormatted }}</span>
                    </h5>
                </div>
                <div :id="'message_' + comment.id" class="panel-collapse collapse" :class="{'in': index == 0}">
                    <div class="box-body">
                        <div v-html="comment.comment"></div>
                    </div>
                </div>
            </div>
        </div>
        <nav aria-label="Page navigation example" v-show="comments.length">
            <ul class="pagination">
                <li class="page-item" :class="{'disabled': currentPage == 1}">
                    <a class="page-link" href="#accordion" @click="getCommentsPage(currentPage - 1)">Previous</a>
                </li>
                <li class="page-item" v-for="page in lastPage" :class="{'active': page == currentPage}">
                    <a class="page-link" href="#accordion" @click="page != currentPage ? getCommentsPage(page) : false">{{ page }}</a>
                </li>
                <li class="page-item" :class="{'disabled': currentPage == lastPage}">
                    <a class="page-link" href="#accordion" @click="getCommentsPage(currentPage + 1)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import CommentEditor from '../partials/summernote';
    import Mixins from '../mixins';

    export default {
        mixins: [Mixins],
        props: ['routeCreate', 'routeGet', 'modelType', 'modelId', 'showForm', 'perPage'],
        components: {
            "comment-editor": CommentEditor,
        },
        data: function () {
            return {
                commentInput: '',
                hasError: false,
                comments: [],
                currentPage: 0,
                lastPage: 0,
            }
        },
        mounted: function () {
            this.getCommentsPage(1);
        },
        methods: {
            saveComment: function () {
                if (!this.commentInput) {
                    this.hasError = true;
                    return;
                }

                let _this = this;

                Swal.fire({
                    text: _t('Are you sure want to send the comment?'),
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    cancelButtonText: _t('No'),
                    confirmButtonText: _t('Yes'),
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-default',
                    }
                }).then(function (result) {
                    if (result.value) {
                        _this.sendCommentToServer();
                    }
                });
            },
            sendCommentToServer: function () {
                axios.post(this.routeCreate, {
                    comment: this.commentInput,
                    model_type: this.modelType,
                    model_id: this.modelId,
                    perPage: this.perPage,
                })
                    .then((res) => {
                        if (res.data.success) {
                            this.commentInput = '';
                            this.hasError = false;
                            this.comments = res.data.data.data;
                            this.currentPage = res.data.data.current_page;
                            this.lastPage = res.data.data.last_page;
                            Swal.fire({
                                text: _t("Comment added successfully!"),
                                icon: "success",
                                showCancelButton: false,
                            })
                        } else {
                            this.hasError = true;
                        }
                    });
            },
            getCommentsPage: function (page) {
                axios.get(this.routeGet, {
                    params: {
                        model_type: this.modelType,
                        model_id: this.modelId,
                        perPage: this.perPage,
                        page: page
                    }
                })
                    .then((res) => {
                        if (res.data.success) {
                            this.comments = res.data.data.data;
                            this.currentPage = res.data.data.current_page;
                            this.lastPage = res.data.data.last_page;
                        }
                    });
            },
        },
        filters: {
            dateFormatted(value) {
                let date = moment(value);
                return date.format('DD/MM/YYYY HH:mm');
            }
        }
    }
</script>
