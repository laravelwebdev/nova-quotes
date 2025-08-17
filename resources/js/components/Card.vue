<template>
  <card class="p-0" style="overflow: hidden;">
    <div class="slider" :style="{ background: currentBackground }">
      <div class="content">
        <transition name="fade" mode="out-in">
          <div
            v-if="slides[current]"
            :key="current"
            class="slide"
            :class="slides[current].type"
          >
            <div v-if="slides[current].type === 'welcome'">
              <h4>{{ slides[current].greetings }}</h4>
              <h2>{{ slides[current].user }}</h2>
              <br />
              <h4>{{ slides[current].description }}</h4>
            </div>
            <div v-else class="quote-container">
              <p class="quote-text">{{ slides[current].text }}</p>
              <p class="quote-author">~ {{ slides[current].author }}</p>
            </div>
          </div>
        </transition>
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
      interval: this.card.refreshInterval,
    };
  },

  computed: {
    currentBackground() {
      const s = this.slides[this.current];
      if (!s) return this.randomGradient();
      if (s.type === "welcome") return s.gradient;
      return this.randomGradient();
    },
  },

  mounted() {
    if (this.slides.length > 0) {
      // Sisipkan welcome sebelum setiap quote
      const welcomeSlide = this.slides.find(s => s.type === 'welcome') || null;
      const quotes = this.slides.filter(s => s.type === 'quote');

      if (welcomeSlide && quotes.length > 0) {
        const newSlides = [];
        quotes.forEach(q => {
          newSlides.push({ ...welcomeSlide });
          newSlides.push(q);
        });
        this.slides = newSlides;
      }

      this.show(0);
      setInterval(
        () => this.show((this.current + 1) % this.slides.length),
        1000 * parseInt(this.interval)
      );
    }
  },

  methods: {
    show(idx) {
      this.current = idx;
    },

    randomGradient() {
      const h1 = Math.floor(Math.random() * 360);
      const h2 = (h1 + Math.floor(Math.random() * 90) + 30) % 360;
      const s = 50 + Math.random() * 20;
      const l = 50 + Math.random() * 10;
      return `linear-gradient(135deg, hsl(${h1}, ${s}%, ${l}%), hsl(${h2}, ${s}%, ${l}%))`;
    },
  },
};
</script>

<style scoped>
.slider {
  position: relative;
  width: 100%;
  min-height: 160px;
  color: #fff;
  overflow: hidden;
  transition: background 0.8s ease-in-out;
}

.content {
  position: relative;
  padding: 20px 24px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: left;
  height: 100%;
}

.slide {
  width: 100%;
  height: 100%;
}

.slide.welcome h4 {
  margin: 0;
  font-weight: 500;
  font-size: 14px;
  opacity: 0.95;
}

.slide.welcome h2 {
  margin: 6px 0 0;
  font-size: 22px;
  font-weight: 700;
}

.quote-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
}

.slide.quote .quote-text {
  margin: 0 0 8px 0;
  font-size: 16px; /* Tetap ukuran default */
  line-height: 1.5;
  word-break: break-word;
}

.slide.quote .quote-author {
  margin: 0;
  font-size: 14px;
  font-style: italic;
  opacity: 0.9;
}

/* Fade animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
