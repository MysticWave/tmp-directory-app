<template>
    <header
        class="flex gap-1 rounded-lg border-2 border-gray-200 p-2 shadow-sm max-sm:flex-col sm:min-h-[56px] sm:rounded-lg dark:border-gray-700 [&>ol]:grow [&>ol>li]:flex [&>ol>li]:items-center"
    >
        <slot />
        <div class="flex flex-wrap gap-1 max-sm:flex-col">
            <slot name="actions" />
        </div>
    </header>
</template>
