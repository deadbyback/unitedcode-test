<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Articles from
                        <a href="https://laravel-news.com/blog">
                            <img class="logo-sm" src="https://laravelnews.imgix.net/laravel-news__logo.png?ixlib=php-3.3.1"  alt="">
                            Laravel News
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <b-table
                            ref="table"
                            id="articles"
                            :fields="fieldNames"
                            :items="articles"
                            :busy="isLoading"
                            :tbody-transition-props="transProps"
                            :current-page="currentPage"
                            :per-page="perPage"
                            class="mt-3"
                            hover
                            sticky-header
                            striped
                            outlined>
                            <template #table-busy>
                                <div class="text-center text-success my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                            <template #cell(date)="data">
                                {{ formattedDate(data.item.date) }}
                            </template>
                            <template #cell(author_id)="data">
                                <p class="card-text" v-html="getAuthor(data.item.author_id)" />
                            </template>
                            <template #cell(image_source)="data">
                                <img class="preload-image" :src="data.value" alt="Image"></img>
                            </template>
                            <template #cell(link)="data">
                                <a :href="data.value">{{ data.value }}</a>
                            </template>
                        </b-table>
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="totalRows"
                            :per-page="perPage"
                            align="fill"
                            size="sm"
                        ></b-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ArticleList",
    data () {
        return {
            articles: [],
            authors: [],
            currentPage: 1,
            perPage: 10,
            fieldNames: [
                { key: 'title', sortable: true },
                { key: 'link', label: 'Original Source' },
                { key: 'date', label: 'Publication date', sortable: true  },
                { key: 'author_id', label: 'Author', sortable: false },
                { key: 'image_source', label: 'Image Source' },
                { key: 'tags'},
            ],
            transProps: {
                name: 'flip-list'
            },
            isLoading: false,
        }
    },
    mounted(){
        this.getAuthors()
        this.getArticles()
    },
    computed: {
      totalRows() {
          return this.articles.length;
      }
    },
    methods:{
        async getArticles(){
            await this.axios.get('/api/articles?page='+this.currentPage).then(response=>{
                this.isLoading = true
                this.articles = response.data
            }).catch(error=>{
                console.log(error)
                this.articles = []
            }).finally(() => {
                this.isLoading = false
            })
        },
        async getAuthors(){
            await this.axios.get('/api/authors').then(response=>{
                this.isLoading = true
                this.authors = response.data
            }).catch(error=>{
                console.log(error)
                this.authors = []
            }).finally(() => {
                this.isLoading = false
            })
        },
        getAuthor(id) {
            let author = this.authors.find(x => x.id === id)
            let link = (typeof author.link !== 'undefined') ? author.link : ''
            let name = (typeof author.name !== 'undefined') ? author.name : 'Unknown'
            return "<a href="+ link +">"+ name +"</a>"
        },
        formattedDate(timestamp) {
            let date = new Date(timestamp * 1000)
            return +date.getDate() + "." + (date.getMonth() + 1) + '.' + date.getFullYear()
        },
    }
}
</script>
<style>
 .b-table-sticky-header {
     overflow-y: auto;
     max-height: 600px;
 }

 th.position-relative {
     position: -webkit-sticky!important;
     position: sticky!important;
     top: 0;
     z-index: 2;
 }
 .preload-image {
     max-width: 120px;
     align-content: center;
 }
</style>
