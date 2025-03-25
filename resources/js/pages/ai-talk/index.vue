<script lang="ts" setup>
import type { TalkType } from './components/types'
import { toast } from '@/components/ui/toast'
import TalkFooter from './components/talk-footer.vue'
import TalkList from './components/talk-list.vue'

const talks = ref<{ type: TalkType, text: string }[]>([
  {
    type: 'system',
    text: `Hello, I'm your smart assistant, how can I help you?`,
  },
  {
    type: 'self',
    text: '你好，我想了解一下你有什么功能',
  },
  {
    type: 'system',
    text: `I can answer your questions, provide information, and help you solve your problems. You can ask me anything and I'll do my best to answer it.`,
  },
  {
    type: 'self',
    text: '你有什么优点和缺点吗？',
  },
  {
    type: 'system',
    text: 'long text example: 我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。我可以回答你的问题，提供信息，帮助你解决问题。你可以问我任何问题，我会尽力回答。',
  },
])

function handleSubmit(text: string) {
  talks.value.push({
    type: 'self',
    text,
  })
}
function handleTypeChange(type: string) {
  toast({
    title: 'type',
    description: h(
      'pre',
      { class: 'mt-2 w-[340px] rounded-md bg-slate-950 p-4' },
      h('code', { class: 'text-white' }, JSON.stringify(type)),
    ),
  })
}
</script>

<template>
  <div class="h-full">
    <div class="flex flex-col h-full">
      <main class="flex-1 overflow-y-auto">
        <TalkList :talks="talks" />
      </main>
      <TalkFooter
        class="sticky bottom-2 bg-background"
        @submit="handleSubmit"
        @type-change="handleTypeChange"
      />
    </div>
  </div>
</template>

<style scoped>

</style>
