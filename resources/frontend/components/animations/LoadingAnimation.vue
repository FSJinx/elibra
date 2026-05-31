<template>
  <div class="dot-parent">
    <span class="dot bouncer"></span>

    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
  </div>
</template>

<style scoped>
.dot-parent {
  position: relative;
  width: 170px;
  height: 40px;
  margin-bottom: 10px;
}

.dot {
  position: absolute;
  width: 16px;
  height: 16px;
  border-radius: 999px;
  background: gainsboro;
  bottom: 0;
}

/* Binigyan natin ng iisang duration (2s) at linear timing para swabe ang pag-usad */
.dot:nth-child(2) {
  left: 30px;
  animation:
    lift 1.5s infinite 0.3s linear,
    shade 1.5s infinite 1.4s linear;
}
.dot:nth-child(3) {
  left: 60px;
  animation:
    lift 1.5s infinite 0.35s linear,
    shade 1.5s infinite 1.3s linear;
}
.dot:nth-child(4) {
  left: 90px;
  animation:
    lift 1.5s infinite 0.4s linear,
    shade 1.5s infinite 1.2s linear;
}
.dot:nth-child(5) {
  left: 120px;
  animation:
    lift 1.5s infinite 0.45s linear,
    shade 1.5s infinite 1.1s linear;
}

.bouncer {
  background: var(--color-primary);
  animation: pulse-travel 1.5s infinite cubic-bezier(0.45, 0.05, 0.55, 0.95);
  z-index: 2;
}

@keyframes pulse-travel {
  0% {
    left: 0px;
  }
  20% {
    left: 0px;
  }

  40% {
    background-color: var(--color-primary);
    left: 150px;
  }
  70% {
    left: 150px;
  }
  75% {
    background-color: gainsboro;
  }
  100% {
    background-color: var(--color-primary);
    left: 0px;
  }
}

@keyframes lift {
  0% {
    transform: translateY(0);
  }
  10% {
    transform: translateY(-30px);
  }
  15% {
    transform: translateY(-30px);
  }
  20% {
    transform: translateY(0);
  }

  100% {
    transform: translateY(0);
  } /* Steady sa baba para sa natitirang oras */
}

/* Ang bawat tuldok ay tatalon lang kapag nadaanan (tumatagal ng 20% ng buong cycle ang talon) */
@keyframes shade {
  0% {
    background-color: gainsboro;
  }
  5% {
    background-color: var(--color-primary);
  } /* Umangat (Mas smooth ang translateY kaysa 'bottom') */
  15% {
    background-color: var(--color-primary);
  } /* Stay sa taas saglit */
  25% {
    background-color: gainsboro;
  } /* Bagsak */
  100% {
    background-color: gainsboro;
  } /* Steady sa baba para sa natitirang oras */
}
</style>
