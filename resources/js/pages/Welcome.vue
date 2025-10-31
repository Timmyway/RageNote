<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { dashboard, login, register } from '@/routes'

defineProps({
  canRegister: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <Head title="RageNote">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head>

  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-zinc-950 to-zinc-900 text-white p-6">
    <header class="w-full max-w-5xl flex justify-between items-center mb-12">
      <h1 class="text-3xl font-bold tracking-tight">
        <span class="text-red-500">Rage</span>Note
      </h1>

      <nav class="flex items-center gap-4">
        <Link
          v-if="$page.props.auth.user"
          :href="dashboard()"
          class="px-5 py-1.5 rounded-lg bg-red-600 hover:bg-red-500 transition text-white font-medium"
        >
          Dashboard
        </Link>
        <template v-else>
          <Link
            :href="login()"
            class="px-4 py-1.5 rounded-md hover:bg-zinc-800 text-sm font-medium"
          >
            Log in
          </Link>
          <Link
            v-if="canRegister"
            :href="register()"
            class="px-4 py-1.5 rounded-md bg-red-600 hover:bg-red-500 text-sm font-medium"
          >
            Register
          </Link>
        </template>
      </nav>
    </header>

    <main class="text-center max-w-2xl">
      <h2 class="text-4xl font-bold mb-4">
        Master your combos.<br />
        Save your rage.
      </h2>

      <p class="text-zinc-400 mb-8">
        RageNote helps Tekken players organize, record, and remember their setups,
        combos, and matchup notes — all in one place.
      </p>

      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <Link
          :href="login()"
          class="px-6 py-3 bg-red-600 hover:bg-red-500 rounded-lg font-semibold transition"
        >
          Get Started
        </Link>
        <a
          href="https://devambition.com"
          target="_blank"
          class="px-6 py-3 border border-zinc-700 hover:bg-zinc-800 rounded-lg font-semibold transition"
        >
          Learn more
        </a>
      </div>
    </main>

    <footer class="mt-16 text-zinc-600 text-sm">
      Made with ❤️ by Tim — © {{ new Date().getFullYear() }}
    </footer>
  </div>
</template>
