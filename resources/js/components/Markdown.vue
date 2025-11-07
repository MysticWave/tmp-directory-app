<script setup>
import MarkdownIt from 'markdown-it';

const props = defineProps({
    source: {
        type: String,
        required: true,
    },
});

const markdown = new MarkdownIt();
</script>

<template>
    <div v-html="markdown.render(source)"></div>
</template>
