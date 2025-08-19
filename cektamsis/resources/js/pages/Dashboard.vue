<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Activity, Users, BookOpen, TrendingUp } from 'lucide-vue-next';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

// form kosong buat logout
const form = useForm({});

const logout = () => {
  form.post(route('logout'));

    form.post(route('logout'));
};
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <!-- Tombol Logout -->
      <div class="flex justify-end mb-4">
        <button
          @click="logout"
          class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
        >
          Logout
        </button>
      </div>

      <!-- Statistik Cards -->
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div
          class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition"
        >
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Users</h3>
            <Users class="h-5 w-5 text-indigo-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">1,245</p>
          <p class="text-xs text-green-400 mt-1">+12% from last month</p>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <!-- Tombol Logout -->
            <div class="flex justify-end mb-4">
                <button
                    @click="logout"
                    class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                >
                    Logout
                </button>
            </div>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>

        <div
          class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition"
        >
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Active Sessions</h3>
            <Activity class="h-5 w-5 text-teal-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">342</p>
          <p class="text-xs text-red-400 mt-1">-5% this week</p>
        </div>

        <div
          class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition"
        >
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Courses</h3>
            <BookOpen class="h-5 w-5 text-purple-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">58</p>
          <p class="text-xs text-green-400 mt-1">+3 new courses</p>
        </div>

        <div
          class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition"
        >
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Revenue</h3>
            <TrendingUp class="h-5 w-5 text-amber-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">$12,450</p>
          <p class="text-xs text-green-400 mt-1">+8% this month</p>
        </div>
      </div>

      <!-- Chart + Activity -->
      <div class="grid gap-6 md:grid-cols-3">
        <!-- Chart Section -->
        <div
          class="relative col-span-2 min-h-[350px] rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg"
        >
          <h3 class="text-lg font-semibold text-white mb-4">User Growth</h3>
          <PlaceholderPattern />
        </div>

        <!-- Recent Activity -->
        <div
          class="relative min-h-[350px] rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg"
        >
          <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
          <ul class="space-y-4 text-sm text-zinc-400">
            <li class="flex justify-between items-center">
              <span>User John registered</span>
              <span class="text-xs text-zinc-500">2m ago</span>
            </li>
            <li class="flex justify-between items-center">
              <span>New course added</span>
              <span class="text-xs text-zinc-500">15m ago</span>
            </li>
            <li class="flex justify-between items-center">
              <span>Revenue updated</span>
              <span class="text-xs text-zinc-500">1h ago</span>
            </li>
            <li class="flex justify-between items-center">
              <span>5 new students enrolled</span>
              <span class="text-xs text-zinc-500">3h ago</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
