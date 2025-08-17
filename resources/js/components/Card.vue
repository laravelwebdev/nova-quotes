<template>
  <card class="p-0" style="overflow: hidden;">
    <div class="slider" :style="{ background: currentBackground }">
      <div class="content" ref="content">
        <transition name="fade" mode="out-in">
          <div
            v-if="slides[current]"
            :key="current"
            class="slide"
            :class="slides[current].type"
          >
            <div v-if="slides[current].type === 'welcome'">
              <h4>Hi, {{ slides[current].name }}</h4>
              <h2>{{ slides[current].title }}</h2>
            </div>
            <div v-else>
              <p class="quote-text" ref="quoteText">{{ slides[current].text }}</p>
              <p class="quote-author" ref="quoteAuthor">~ {{ slides[current].author }}</p>
            </div>
          </div>
        </transition>
      </div>

      <div class="dots">
        <span
          v-for="(s, i) in slides"
          :key="i"
          class="dot"
          :class="{ active: i === current }"
          @click="show(i)"
        />
      </div>
    </div>
  </card>
</template>

<script>
export default {
  props: ["card"],

  data() {
    return {
      current: 0,
      slides: this.card.slides || [],
    };
  },

  computed: {
      currentBackground() {
        const s = this.slides[this.current];
        if (!s) return this.randomGradient();
        if (s.type === "welcome") return s.gradient;
        return this.randomGradient();
      }
  },

  mounted() {
    if (this.slides.length > 0) {
      this.show(0);
      setInterval(() => this.show((this.current + 1) % this.slides.length), 5000);
    }
  },

  methods: {
    show(idx) {
      this.current = idx;
      this.$nextTick(() => {
        this.fitQuote();
      });
    },

    randomGradient() {
      const h1 = Math.floor(Math.random() * 360);
      const h2 = (h1 + Math.floor(Math.random() * 90) + 30) % 360; // beda hue minimal 30°
      
      const s = 50 + Math.random() * 20; // 50–70%
      const l = 50 + Math.random() * 10; // 50–60%

      return `linear-gradient(135deg,
        hsl(${h1}, ${s}%, ${l}%),
        hsl(${h2}, ${s}%, ${l}%))`;
    },

    fitQuote() {
      if (!this.slides[this.current] || this.slides[this.current].type !== "quote") return;
      const textEl = this.$refs.quoteText;
      const authorEl = this.$refs.quoteAuthor;
      if (!textEl || !authorEl) return;

      let size = 16;
      textEl.style.fontSize = size + "px";
      authorEl.style.fontSize = size - 2 + "px";

      const maxH = this.$refs.content.clientHeight;
      while (textEl.scrollHeight + authorEl.scrollHeight > maxH && size > 10) {
        size -= 1;
        textEl.style.fontSize = size + "px";
        authorEl.style.fontSize = size - 2 + "px";
      }
    },
  },
};
</script>
<style scoped> .slider { position: relative; width: 100%; min-height: 160px; color: #fff; border-radius: 12px; overflow: hidden; transition: background 0.8s ease-in-out; } .content { position: absolute; inset: 0; padding: 20px 24px 44px 24px; display: flex; align-items: center; text-align: left; } .slide { width: 100%; } .slide.welcome h4 { margin: 0; font-weight: 500; font-size: 14px; opacity: 0.95; } .slide.welcome h2 { margin: 6px 0 0; font-size: 22px; font-weight: 700; } .slide.quote .quote-text { margin: 0 0 8px 0; font-size: 16px; line-height: 1.5; } .slide.quote .quote-author { margin: 0; font-size: 14px; font-style: italic; opacity: 0.9; } /* Dots */ .dots { position: absolute; left: 50%; bottom: 10px; transform: translateX(-50%); display: flex; gap: 8px; align-items: center; } .dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(0, 0, 0, .35); box-shadow: 0 0 0 1px rgba(255, 255, 255, .25) inset; cursor: pointer; transition: transform .2s ease, background .2s ease, box-shadow .2s ease; } .dot.active { background: #fff; box-shadow: 0 0 4px rgba(255, 255, 255, 0.8), 0 0 0 1px rgba(0, 0, 0, .25) inset; } .dot:hover { transform: scale(1.08); } /* Animasi fade */ .fade-enter-active, .fade-leave-active { transition: opacity .4s; } .fade-enter, .fade-leave-to { opacity: 0; } </style>