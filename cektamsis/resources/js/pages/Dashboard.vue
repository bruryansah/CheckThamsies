<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Activity, Users, BookOpen, TrendingUp } from 'lucide-vue-next';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
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
        </div>

        <div
          class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition"
        >
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Siswa</h3>
            <GraduationCap class="h-5 w-5 text-green-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalSiswa }}</p>
        </div>

        <!-- Total Guru -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Guru</h3>
            <User class="h-5 w-5 text-purple-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalGuru }}</p>
        </div>

        <!-- Total Admin -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Admin</h3>
            <Shield class="h-5 w-5 text-amber-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalAdmin }}</p>
        </div>
      </div>

      <!-- Chart + Activity -->
      <div class="grid gap-6 md:grid-cols-3 mt-6">
        <!-- Chart Section -->
        <div class="relative col-span-2 min-h-[350px] rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
          <h3 class="text-lg font-semibold text-white mb-4">User Growth</h3>
          <PlaceholderPattern />
        </div>

        <!-- Recent Activity -->
        <div class="relative min-h-[350px] rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
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
