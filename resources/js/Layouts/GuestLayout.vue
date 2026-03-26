<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="grid min-h-screen grid-cols-1 xl:grid-cols-2">
        <!-- Left: form side -->
        <div class="flex items-center justify-center px-10 md:px-20">
            <div class="flex w-full max-w-sm flex-col justify-center py-16">
                <Link href="/" class="mb-16 text-6xl font-bold tracking-tight">
                    Workshop
                </Link>

                <slot />
            </div>
        </div>

        <!-- Right: gradient side -->
        <div
            class="hidden bg-cover bg-center xl:block"
            style="
                background-image: url('/grad.jpg');
                background-repeat: no-repeat;
                background-size: cover;
            "
        />
    </div>
</template>
