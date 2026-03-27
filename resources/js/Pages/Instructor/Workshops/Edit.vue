<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Separator } from '@/Components/ui/separator';
import { Textarea } from '@/Components/ui/textarea';
import WorkshopCard from '@/Components/WorkshopCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Check, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const auth = usePage().props.auth;

const props = defineProps<{
    workshop: {
        id: number;
        name: string;
        description: string;
        duration_minutes: number;
        price_per_person: number;
        archived: boolean;
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            status: string;
            bookings_sum_headcount: number | null;
        }>;
    };
}>();

const form = useForm({
    name: props.workshop.name,
    description: props.workshop.description,
    duration_minutes: props.workshop.duration_minutes,
    price_per_person: props.workshop.price_per_person,
});

function archive() {
    if (confirm('Workshop archiválása?')) {
        router.patch(route('instructor.workshops.archive', props.workshop.id));
    }
}

const preview = computed(() => ({
    id: props.workshop.id,
    name: form.name || 'Workshop neve',
    description: '',
    duration_minutes: form.duration_minutes,
    price_per_person: form.price_per_person,
    instructor: { name: auth.user.name ?? 'Név' },
    sessions: props.workshop.sessions,
}));
</script>

<template>
    <Head title="Edit Workshop" />
    <AppLayout>
        <div
            class="mx-auto grid min-h-screen max-w-7xl grid-cols-1 gap-8 bg-background px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:grid-cols-[1fr_0.4fr] lg:px-8"
        >
            <section class="flex flex-col gap-8">
                <Button as-child variant="ghost" size="lg" class="w-24">
                    <Link :href="route('instructor.workshops.index')">
                        <ArrowLeft />
                        Vissza
                    </Link>
                </Button>

                <!-- Header -->
                <header
                    class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
                >
                    <h1
                        class="max-w-2xl text-4xl font-bold leading-tight text-primary md:text-6xl"
                    >
                        Workshop szerkesztése
                    </h1>
                </header>

                <Separator />

                <form
                    id="workshop-edit-form"
                    @submit.prevent="
                        form.patch(
                            route('instructor.workshops.update', workshop.id),
                        )
                    "
                    class="flex flex-col gap-4"
                >
                    <div>
                        <Label>Név</Label>
                        <Input v-model="form.name" />
                        <p v-if="form.errors.name">
                            {{ form.errors.name }}
                        </p>
                    </div>
                    <div>
                        <Label>Leírás</Label>
                        <Textarea v-model="form.description" />
                        <p v-if="form.errors.description">
                            {{ form.errors.description }}
                        </p>
                    </div>
                    <div>
                        <Label>Időtartam (perc)</Label>
                        <Input type="number" v-model="form.duration_minutes" />
                        <p v-if="form.errors.duration_minutes">
                            {{ form.errors.duration_minutes }}
                        </p>
                    </div>
                    <div>
                        <Label>Ár / fő (Ft)</Label>
                        <Input type="number" v-model="form.price_per_person" />
                        <p v-if="form.errors.price_per_person">
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
                    form="workshop-edit-form"
                >
                    <Check />
                    Workshop mentése
                </Button>
                <Button
                    size="lg"
                    class="h-14"
                    variant="destructive"
                    @click="archive"
                >
                    <Trash2 />
                    Workshop törlése
                </Button>

                <WorkshopCard :workshop="preview" :preview="true" />
            </aside>
        </div>
    </AppLayout>
</template>
