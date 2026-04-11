<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Separator } from '@/Components/ui/separator';
import { Textarea } from '@/Components/ui/textarea';
import WorkshopCard from '@/Components/WorkshopCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { goBack } from '@/lib/utils';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Check } from 'lucide-vue-next';
import { computed } from 'vue';

const auth = usePage().props.auth;

const props = defineProps<{
    instructor?: {
        id: number;
        name: string;
    };
    store_url: string;
}>();

const form = useForm({
    name: '',
    description: '',
    duration_minutes: 60,
    price_per_person: 0,
});

const preview = computed(() => ({
    id: 0,
    name: form.name || 'Workshop neve',
    description: form.description || 'Leírás...',
    duration_minutes: form.duration_minutes,
    price_per_person: form.price_per_person,
    instructor: { name: props.instructor?.name ?? 'Név' },
    sessions: [],
}));
</script>

<template>
    <Head title="Új workshop" />
    <AppLayout>
        <div
            class="mx-auto grid min-h-screen max-w-7xl grid-cols-1 gap-8 bg-background px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:grid-cols-[1fr_0.4fr] lg:px-8"
        >
            <section class="flex flex-col gap-8">
                <Button @click="goBack" variant="ghost" size="lg" class="w-24">
                    <ArrowLeft />
                    Vissza
                </Button>

                <header
                    class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
                >
                    <h1
                        class="max-w-2xl text-4xl font-bold leading-tight text-primary md:text-6xl"
                    >
                        Új workshop
                    </h1>
                </header>

                <Separator />

                <form
                    id="workshop-create-form"
                    @submit.prevent="form.post(store_url)"
                    class="flex flex-col gap-4"
                >
                    <div>
                        <Label>Név</Label>
                        <Input v-model="form.name" />
                        <p
                            v-if="form.errors.name"
                            class="text-md text-destructive"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>
                    <div>
                        <Label>Leírás</Label>
                        <Textarea v-model="form.description" />
                        <p
                            v-if="form.errors.description"
                            class="text-md text-destructive"
                        >
                            {{ form.errors.description }}
                        </p>
                    </div>
                    <div>
                        <Label>Időtartam (perc)</Label>
                        <Input type="number" v-model="form.duration_minutes" />
                        <p
                            v-if="form.errors.duration_minutes"
                            class="text-md text-destructive"
                        >
                            {{ form.errors.duration_minutes }}
                        </p>
                    </div>
                    <div>
                        <Label>Ár / fő (Ft)</Label>
                        <Input type="number" v-model="form.price_per_person" />
                        <p
                            v-if="form.errors.price_per_person"
                            class="text-md text-destructive"
                        >
                            {{ form.errors.price_per_person }}
                        </p>
                    </div>
                </form>
            </section>

            <aside
                class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start"
            >
                <Button
                    type="submit"
                    :disabled="form.processing"
                    size="lg"
                    class="h-20"
                    form="workshop-create-form"
                >
                    <Check />
                    Workshop létrehozása
                </Button>

                <WorkshopCard :workshop="preview" :preview="true" />
            </aside>
        </div>
    </AppLayout>
</template>
