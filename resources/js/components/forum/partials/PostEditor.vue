<template>
  <div class="editor-tools m-0 mb-1 px-1 sticky-top sticky-header-tab" v-if="editor && props.editable">
    <button class="btn" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
      <i class="bi bi-type-bold"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
      <i class="bi bi-type-italic"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
      <i class="bi bi-type-strikethrough"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'is-active': editor.isActive('underline') }">
      <i class="bi bi-type-underline"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().setParagraph().run()" :class="{ 'is-active': editor.isActive('paragraph') }">
      <i class="bi bi-paragraph"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().setTextAlign('left').run()" :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }">
      <i class="bi bi-text-left"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().setTextAlign('center').run()" :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }">
      <i class="bi bi-text-center"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().setTextAlign('right').run()" :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }">
      <i class="bi bi-text-right"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">
      H1
    </button>
    <button class="btn" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">
      H2
    </button>
    <button class="btn" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">
      H3
    </button>
    <button class="btn" @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }">
      H4
    </button>
    <button class="btn" @click="editor.chain().focus().toggleHeading({ level: 5 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }">
      H5
    </button>
    <button class="btn" @click="editor.chain().focus().toggleHeading({ level: 6 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }">
      H6
    </button>
    <button class="btn" @click="setLink" :class="{ 'is-active': editor.isActive('link') }">
      <i class="bi bi-link-45deg"></i>
    </button>
    <button class="btn" @click="addImage" :class="{ 'is-active': editor.isActive('image') }">
      <i class="bi bi-image"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">
      <i class="bi bi-list-ul"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor.isActive('orderedList') }">
      <i class="bi bi-list-ol"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'is-active': editor.isActive('blockquote') }">
      <i class="bi bi-blockquote-left"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().setHorizontalRule().run()">
      <i class="bi bi-hr"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().undo().run()">
      <i class="bi bi-arrow-counterclockwise"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().redo().run()">
      <i class="bi bi-arrow-clockwise"></i>
    </button>
    <button class="btn" @click="editor.chain().focus().setHardBreak().run()">
      <i class="bi bi-arrow-return-left"></i> <!-- hard break --> 
    </button>
  </div>
  <editor-content :editor="editor" class="m-0 p-0 editor-content"/>
</template>

<script setup>
    import { useEditor, EditorContent } from '@tiptap/vue-3'
    import StarterKit from '@tiptap/starter-kit'
    import Underline from '@tiptap/extension-underline'
    import Image from '@tiptap/extension-image'
    import TextAlign from '@tiptap/extension-text-align'
    import Link from '@tiptap/extension-link'
    import { onUnmounted, watch } from '@vue/runtime-core'

    const props = defineProps({
        modelValue: {
            type: String,
            default: '<p>Please write something...</p>'
        },
        editable: {
          type: Boolean,
          default: false
        }
    })
    const emits = defineEmits([
        'update:modelValue'
    ])
    const editor = useEditor({
        content: props.modelValue,
        editable: props.editable,
        extensions: [
            StarterKit,
            Underline,
            Image,
            TextAlign.configure({
              types: ['heading', 'paragraph', 'image'],
            }),
            Link
        ],
        onUpdate: ({ editor }) => {
          // updates modelValue on change
          emits('update:modelValue', editor.getHTML())
        }
    })

    // editor functions
    const addImage = (() => {
      const url = window.prompt('URL')

      if (url) {
        editor.value.chain().focus().setImage({ src: url }).run()
      }
    })
    const setLink = (() => {
      const previousUrl = editor.value.getAttributes('link').href
      const url = window.prompt('URL', previousUrl)

      // cancelled
      if (url === null) {
        return
      }

      // empty
      if (url === '') {
        editor.value
          .chain()
          .focus()
          .extendMarkRange('link')
          .unsetLink()
          .run()

        return
      }

      // update link
      editor.value
        .chain()
        .focus()
        .extendMarkRange('link')
        .setLink({ href: url })
        .run()
    })
    watch(() => props.modelValue, (newValue, oldValue) => {
      // updates editor on prop change (for right sidebar, when hover posts)
      if (!editor.value.isEditable)
        editor.value.commands.setContent(newValue)
    });

    onUnmounted(() => {
        editor.value.destroy()
    })
</script>

<style scoped>
    :deep(.ProseMirror) {
      padding: 1rem;
      z-index: 1 !important;
      min-height: 360px;
    }
    :deep(.ProseMirror-selectednode) {
      outline: #0D6EFD solid 2px;
    }
    .is-active {
      background-color: #0D6EFD !important;
      color: white !important;
    }
    .editor-tools button {
      font-size: 1rem !important;
      border: 1px solid black;
      background-color: #fff;
    }
    :deep(.ProseMirror img) {
      max-width: 100%;
      height: auto;
    }
</style>
