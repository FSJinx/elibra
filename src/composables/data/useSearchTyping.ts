import { ref, onMounted, onUnmounted } from 'vue'

export function useSearchTyping() {
  const searchExamples = ['books about programming', 'Philippine history', 'artificial intelligence', 'computer science textbooks', 'books about agriculture', 'books by José Rizal']

  const typedText = ref('')

  let typingTimer: ReturnType<typeof setTimeout>
  let deletingTimer: ReturnType<typeof setTimeout>
  let sentenceTimer: ReturnType<typeof setTimeout>

  const typeSentence = (sentence: string) => {
    let index = 0
    typedText.value = ''

    const type = () => {
      if (index < sentence.length) {
        typedText.value += sentence[index]
        index++

        typingTimer = setTimeout(type, 45)
      } else {
        sentenceTimer = setTimeout(() => {
          deleteSentence(sentence)
        }, 1800)
      }
    }

    type()
  }

  const deleteSentence = (sentence: string) => {
    let index = sentence.length

    const deleteText = () => {
      if (index > 0) {
        index--
        typedText.value = sentence.substring(0, index)

        deletingTimer = setTimeout(deleteText, 25)
      } else {
        const nextSentence = searchExamples[Math.floor(Math.random() * searchExamples.length)]

        sentenceTimer = setTimeout(() => {
          typeSentence(nextSentence)
        }, 400)
      }
    }

    deleteText()
  }

  onMounted(() => {
    const randomSentence = searchExamples[Math.floor(Math.random() * searchExamples.length)]

    typeSentence(randomSentence)
  })

  onUnmounted(() => {
    clearTimeout(typingTimer)
    clearTimeout(deletingTimer)
    clearTimeout(sentenceTimer)
  })

  return {
    typedText,
  }
}
