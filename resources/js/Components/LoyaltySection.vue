<template>
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                <!-- Left: Steps -->
                <div>
                    <span
                        class="block text-sm font-bold uppercase tracking-widest text-primary/50"
                    >
                        Lépésről lépésre
                    </span>
                    <h2
                        class="mt-2 text-4xl font-extrabold tracking-tight text-primary"
                    >
                        Kezdd el az utazást még ma
                    </h2>

                    <div class="mt-12 space-y-10">
                        <div
                            v-for="step in steps"
                            :key="step.number"
                            class="flex gap-6"
                        >
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-xl font-black text-primary-foreground"
                            >
                                {{ step.number }}
                            </div>
                            <div>
                                <h4 class="text-xl font-bold">
                                    {{ step.title }}
                                </h4>
                                <p class="mt-1 text-muted-foreground">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Loyalty Card -->
                <Card
                    class="relative overflow-hidden bg-amber-700 p-2 text-orange-300"
                >
                    <CardHeader class="flex flex-row items-center gap-2">
                        <Sparkles />
                        <h3 class="text-3xl font-extrabold">Hűségprogram</h3>
                    </CardHeader>

                    <CardContent class="relative z-10 space-y-6">
                        <p class="text-lg leading-relaxed opacity-90">
                            Minden workshop közelebb visz az exkluzív
                            jutalmakhoz. Regisztrált felhasználóink
                            <strong>Pont</strong>okat gyűjtenek minden vásárlás
                            és látogatás után.
                        </p>

                        <div
                            class="space-y-3 rounded-2xl bg-white/10 p-6 text-orange-200"
                        >
                            <h4 class="font-bold">Hogyan működik:</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center gap-2">
                                    <CircleCheck />
                                    100 Pont = 10% kedvezmény
                                </li>
                                <li class="flex items-center gap-2">
                                    <CircleCheck />
                                    Korai hozzáférés az új workshopokhoz
                                </li>
                            </ul>
                        </div>

                        <Button
                            as-child
                            variant="secondary"
                            size="lg"
                            class="w-full"
                        >
                            <Link :href="route('register')"
                                >Csatlakozz a programhoz</Link
                            >
                        </Button>
                    </CardContent>

                    <!-- Decorative blob -->
                    <div
                        class="pointer-events-none absolute -right-12 -top-12 h-64 w-64 rounded-full bg-white/10 blur-3xl"
                    ></div>
                </Card>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Link } from '@inertiajs/vue3';
import { CircleCheck, Sparkles } from 'lucide-vue-next';

const steps = [
    {
        number: 1,
        title: 'Böngéssz és válassz',
        description:
            'Válogass szakemberek által vezetett kreatív workshopjaink közül.',
    },
    {
        number: 2,
        title: 'Gyors foglalás',
        description:
            'Foglald le helyed azonnal egyszerűsített fizetési folyamatunkkal.',
    },
    {
        number: 3,
        title: 'Gyere el és alkoss',
        description:
            'Érkezz meg a stúdióba, találkozz az oktatóddal, és hagyd, hogy áramoljon a kreativitás.',
    },
];
</script>
