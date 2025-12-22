<template>
    <div v-if="article != null" class="alert alert-primary" role="alert">
        Добавлена новая статья <strong> <a :href="`/article/${article.id}`"> {{ article.name }}</a></strong>
    </div>

</template>


<script>

    export default {

    data() { return { article: null } },

        created() {

            window.Echo.channel('articles')
      .listen('.NewArticleEvent', (data) => {
        this.article = data.article;
        alert('Добавлена новая статья!');
      });

        }

    }

</script>