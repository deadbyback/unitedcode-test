const Home = () => import('./components/Home.vue')
const ArticleList = () => import('./components/Blog/ArticleList.vue')

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'articleList',
        path: '/articleList',
        component: ArticleList
    },
]
