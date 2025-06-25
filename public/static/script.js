/**
 * COPRO ACADEMY - Optimized JavaScript with Masonry Layout
 * CORRECTIONS: Articles visibility, load more button, dynamic carousel
 */

// ===================================
// UTILITIES (inchangé)
// ===================================
const utils = {
  delay: (ms) => new Promise((res) => setTimeout(res, ms)),
  debounce: (fn, wait) => {
    let timeout;
    return (...args) => {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn.apply(this, args), wait);
    };
  },
  formatDate: (date) =>
    new Date(date).toLocaleDateString("fr-FR", {
      year: "numeric",
      month: "long",
      day: "numeric",
    }),
  hide: (el) => {
    el.style.display = "none";
    el.classList.add("filtered-hidden");
  },
  show: (el) => {
    el.style.display = "block";
    el.classList.remove("filtered-hidden");
  },
  getUrlParam: (name) => new URLSearchParams(window.location.search).get(name),
};

// ===================================
// CAROUSEL DYNAMIQUE
// ===================================
class Carousel {
  constructor(selector, options = {}) {
    this.track = document.querySelector(selector);
    this.images = this.track?.querySelectorAll(".carousel-image") || [];
    this.currentIndex = 0;
    this.intervalTime = options.interval || 4000;
    this.intervalId = null;
    this.autoSlide = options.autoSlide !== false;
    this.imageData = options.images || [];

    this.init();
  }

  init() {
    // Si des images dynamiques sont fournies, les créer
    if (this.imageData.length && this.track) {
      this.createImages();
    }

    if (!this.images.length) return;

    this.show(0);
    if (this.autoSlide) {
      this.start();
      this.track.addEventListener("mouseenter", () => this.stop());
      this.track.addEventListener("mouseleave", () => this.start());
    }

    this.createControls();
  }

  createImages() {
    this.track.innerHTML = "";
    this.imageData.forEach((img, idx) => {
      const imgEl = document.createElement("div");
      imgEl.className = "carousel-image";
      imgEl.innerHTML = `
        <img src="${img.src}" alt="${img.alt || ""}" loading="${
        idx === 0 ? "eager" : "lazy"
      }">
        ${
          img.caption
            ? `<div class="carousel-caption">${img.caption}</div>`
            : ""
        }
      `;
      this.track.appendChild(imgEl);
    });
    this.images = this.track.querySelectorAll(".carousel-image");
  }

  createControls() {
    if (this.images.length <= 1) return;

    const controls = document.createElement("div");
    controls.className = "carousel-controls";
    controls.innerHTML = `
      <button class="carousel-btn carousel-prev" aria-label="Image précédente">‹</button>
      <button class="carousel-btn carousel-next" aria-label="Image suivante">›</button>
    `;

    this.track.appendChild(controls);

    controls
      .querySelector(".carousel-prev")
      .addEventListener("click", () => this.prev());
    controls
      .querySelector(".carousel-next")
      .addEventListener("click", () => this.next());
  }

  show(i) {
    this.images.forEach((img) => img.classList.remove("active"));
    this.images[i].classList.add("active");
    this.currentIndex = i;
  }

  next() {
    this.show((this.currentIndex + 1) % this.images.length);
  }

  prev() {
    this.show(
      (this.currentIndex - 1 + this.images.length) % this.images.length
    );
  }

  start() {
    this.stop();
    this.intervalId = setInterval(() => this.next(), this.intervalTime);
  }

  stop() {
    clearInterval(this.intervalId);
  }
}

// ===================================
// MASONRY LAYOUT (inchangé)
// ===================================
class Masonry {
  constructor(selector, gap = 15) {
    this.container = document.querySelector(selector);
    this.gap = gap;
    this.isList = false;
    this._bindResize();
    this.refresh();
  }

  _getCols() {
    const w = this.container.offsetWidth;
    if (w < 768) return 1;
    if (w < 1024) return 2;
    return 3;
  }

  _getColWidth(cols) {
    return (this.container.offsetWidth - (cols - 1) * this.gap) / cols;
  }

  _reset() {
    this.cols = this._getCols();
    this.heights = Array(this.cols).fill(0);
    this.items = Array.from(this.container.querySelectorAll(".card"));
    this.container.style.position = "relative";
  }

  _position() {
    if (this.isList) return;
    const colW = this._getColWidth(this.cols);
    this.items.forEach((el) => {
      if (el.classList.contains("filtered-hidden")) return;
      const span = el.classList.contains("card-wide") && this.cols > 1 ? 2 : 1;
      let idx = 0;
      if (span > 1) {
        let minH = Infinity;
        for (let i = 0; i <= this.cols - span; i++) {
          const h = Math.max(...this.heights.slice(i, i + span));
          if (h < minH) {
            minH = h;
            idx = i;
          }
        }
      } else {
        idx = this.heights.indexOf(Math.min(...this.heights));
      }
      const x = idx * (colW + this.gap);
      const y = this.heights[idx];
      Object.assign(el.style, {
        position: "absolute",
        width: `${colW * span + this.gap * (span - 1)}px`,
        left: `${x}px`,
        top: `${y}px`,
      });
      const h = el.offsetHeight;
      for (let i = idx; i < idx + span; i++) this.heights[i] = y + h + this.gap;
    });
    this.container.style.height = `${Math.max(...this.heights)}px`;
  }

  refresh() {
    if (!this.container) return;
    this._reset();
    setTimeout(() => this._position(), 50);
  }

  setList(isList) {
    this.isList = isList;
    this.container.style.height = isList ? "auto" : this.container.style.height;
    this.refresh();
  }

  _bindResize() {
    window.addEventListener("resize", () => {
      clearTimeout(this._resizeTimer);
      this._resizeTimer = setTimeout(() => this.refresh(), 200);
    });
  }
}

// ===================================
// ARTICLES PAGE - CORRIGÉ
// ===================================
class ArticlesPage {
  constructor() {
    this.container = document.getElementById("articlesContainer");
    if (!this.container) return;

    this.filterBtns = document.querySelectorAll(".filter-btn");
    this.gridBtn = document.getElementById("gridView");
    this.listBtn = document.getElementById("listView");
    this.loadBtn = document.getElementById("loadMore");

    this.allItems = Array.from(this.container.querySelectorAll(".card"));
    this.itemsPerLoad = 6;
    this.currentIndex = 8; // Afficher 8 articles par défaut
    this.isListView = false;

    // CORRECTION: Initialiser le filtre par défaut
    this.currentFilter = "all";

    // Définir le bouton actif par défaut
    this._setActiveFilter();

    this.masonry = new Masonry("#articlesContainer");

    this._bind();
    this._applyFilter();
  }

  _setActiveFilter() {
    // Activer le premier bouton ou le bouton "all" par défaut
    const allBtn = Array.from(this.filterBtns).find(
      (btn) => btn.dataset.type === "all"
    );
    const defaultBtn = allBtn || this.filterBtns[0];

    if (defaultBtn) {
      this.filterBtns.forEach((btn) => btn.classList.remove("active"));
      defaultBtn.classList.add("active");
      this.currentFilter = defaultBtn.dataset.type || "all";
    }
  }

  _bind() {
    this.filterBtns.forEach((btn) =>
      btn.addEventListener("click", () => this._filter(btn))
    );
    this.gridBtn?.addEventListener("click", () => this._toggleView(false));
    this.listBtn?.addEventListener("click", () => this._toggleView(true));
    this.loadBtn?.addEventListener("click", () => this._loadMore());
  }

  _filter(btn) {
    this.filterBtns.forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");
    this.currentFilter = btn.dataset.type;
    this.currentIndex = 8; // Reset à 8 articles
    this._applyFilter();
  }

  _applyFilter() {
    // CORRECTION: Vérifier que currentFilter est défini
    const filter = this.currentFilter || "all";

    this.allItems.forEach((item, idx) => {
      const matches = filter === "all" || item.dataset.type === filter;
      if (matches && idx < this.currentIndex) {
        utils.show(item);
      } else {
        utils.hide(item);
      }
    });

    this.masonry.refresh();
    this._updateLoadBtn();
  }

  _toggleView(list) {
    this.isListView = list;
    this.container.classList.toggle("list-view", list);
    this.gridBtn?.classList.toggle("active", !list);
    this.listBtn?.classList.toggle("active", list);
    this.masonry.setList(list);
  }

  _loadMore() {
    if (!this.loadBtn) return;

    this.loadBtn.disabled = true;
    const nextIndex = Math.min(
      this.currentIndex + this.itemsPerLoad,
      this._getFilteredItems().length
    );

    utils.delay(300).then(() => {
      this.currentIndex = nextIndex;
      this._applyFilter();
      this.loadBtn.disabled = false;
    });
  }

  _getFilteredItems() {
    return this.allItems.filter(
      (item) =>
        this.currentFilter === "all" || item.dataset.type === this.currentFilter
    );
  }

  _updateLoadBtn() {
    if (!this.loadBtn) return;

    const filteredItems = this._getFilteredItems();
    const hasMore = filteredItems.length > this.currentIndex;
    this.loadBtn.style.display = hasMore ? "block" : "none";
  }
}

// ===================================
// NAVBAR, NEWSLETTER, CONTACT (inchangés)
// ===================================
const navbar = {
  init() {
    const burger = document.querySelector(".navbar__burger");
    const nav = document.querySelector(".navbar__nav");
    if (!burger || !nav) return;

    burger.addEventListener("click", () => {
      const open = nav.classList.toggle("navbar__nav--open");
      burger.setAttribute("aria-expanded", open);
    });
    document.addEventListener("click", (e) => {
      if (!burger.contains(e.target) && !nav.contains(e.target)) {
        nav.classList.remove("navbar__nav--open");
        burger.setAttribute("aria-expanded", "false");
      }
    });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && nav.classList.contains("navbar__nav--open")) {
        nav.classList.remove("navbar__nav--open");
        burger.setAttribute("aria-expanded", "false");
        burger.focus();
      }
    });
  },
};

const newsletter = {
  init() {
    const form = document.getElementById("newsletterForm");
    if (!form) return;
    form.addEventListener("submit", (e) => this._submit(e));
  },

  async _submit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const orig = btn.textContent;
    btn.textContent = "Inscription...";
    btn.disabled = true;
    await utils.delay(1500);
    btn.textContent = "✓ Inscrit !";
    btn.style.background = "#2ecc71";
    setTimeout(() => {
      btn.textContent = orig;
      btn.disabled = false;
      btn.style.background = "";
    }, 3000);
  },
};

const contactForm = {
  init() {
    const form = document.getElementById("contactForm");
    if (!form) return;
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      this._handleSubmit(form);
    });
    form.querySelectorAll("input,textarea,select").forEach((field) => {
      field.addEventListener("blur", () => this._validateField(field));
      field.addEventListener("input", () => this._clearFieldError(field));
    });
    this._prefillSubject();
  },

  _validateField(field) {
    const val = field.value.trim();
    let msg = "";
    if (field.hasAttribute("required") && !val)
      msg = "Ce champ est obligatoire";
    else if (field.type === "email" && val && !/^[^@]+@[^@]+\.[^@]+$/.test(val))
      msg = "Email invalide";
    else if (field.name === "message" && val.length && val.length < 20)
      msg = "Au moins 20 caractères";
    if (msg) this._showError(field, msg);
    else this._clearFieldError(field);
    return !msg;
  },

  _showError(field, msg) {
    field.classList.add("error");
    field.setAttribute("aria-invalid", "true");
    let err = document.getElementById(field.id + "-error");
    if (!err) {
      err = document.createElement("div");
      err.id = field.id + "-error";
      err.className = "form-error";
      field.after(err);
    }
    err.textContent = msg;
    err.classList.add("form-error--show");
  },

  _clearFieldError(field) {
    field.classList.remove("error");
    field.removeAttribute("aria-invalid");
    const err = document.getElementById(field.id + "-error");
    if (err) err.textContent = "";
  },

  announce(errors) {
    const a = document.createElement("div");
    a.role = "alert";
    a.className = "sr-only";
    a.textContent = `Le formulaire contient ${errors} erreur${
      errors > 1 ? "s" : ""
    }.`;
    document.body.appendChild(a);
    setTimeout(() => a.remove(), 3000);
  },

  async _handleSubmit(form) {
    let errs = 0;
    form.querySelectorAll("input,textarea,select").forEach((f) => {
      if (!this._validateField(f)) errs++;
    });
    if (errs) {
      this.announce(errs);
      form.querySelector(".error")?.focus();
      return;
    }
    const btn = form.querySelector('button[type="submit"]');
    const orig = btn.textContent;
    btn.textContent = "Envoi en cours...";
    btn.disabled = true;
    await utils.delay(2000);
    btn.textContent = "✓ Message envoyé !";
    btn.style.background = "#2ecc71";
    setTimeout(() => {
      form.reset();
      btn.textContent = orig;
      btn.disabled = false;
      btn.style.background = "";
    }, 3000);
  },

  _prefillSubject() {
    const sujet = utils.getUrlParam("sujet");
    if (!sujet) return;
    const sel = document.getElementById("sujet");
    if (sel && sel.querySelector(`option[value="${sujet}"]`)) sel.value = sujet;
  },
};

const articleDetail = {
  init(dataMap) {
    const hero = document.querySelector(".article-hero");
    if (!hero) return;
    const id = utils.getUrlParam("id");
    if (!id || !dataMap[id]) return (window.location.href = "articles.html");
    const art = dataMap[id];
    document.title = `${art.title} - Copro Academy`;
    document.getElementById("article-category").textContent = art.category;
    document.getElementById("article-date").textContent = utils.formatDate(
      art.date
    );
    document.getElementById("article-title").textContent = art.title;
    document.getElementById("article-summary").textContent = art.summary;
    document.getElementById("article-author").textContent = art.author;
    document.getElementById("reading-time").textContent = art.readingTime;
    document.querySelectorAll(".share-btn").forEach((btn) =>
      btn.addEventListener("click", () => {
        if (navigator.share)
          navigator.share({ title: document.title, url: window.location.href });
        else
          navigator.clipboard
            .writeText(window.location.href)
            .then(() => alert("Lien copié !"));
      })
    );
  },
};

// ===================================
// INITIALIZATION - CORRIGÉE
// ===================================
document.addEventListener("DOMContentLoaded", () => {
  // Carousel dynamique avec exemple d'images
  const carouselImages = [
    {
      src: "assets/collegues-de-taille-moyenne-apprenant.jpg",
      alt: "Copropriété moderne",
      caption: "Gestion moderne de copropriété",
    },
    {
      src: "assets/agent-immobilier-masculin-faisant-des-affaires-et-montrant-la-maison-a-un-couple-d-acheteurs-potentiels.jpg",
      alt: "Assemblée générale",
      caption: "Assemblées générales efficaces",
    },
    {
      src: "assets/tenir-la-cle-a-la-main-a-l-exterieur.jpg",
      alt: "Maintenance",
      caption: "Maintenance préventive",
    },
  ];

  new Carousel("#carouselTrack", {
    images: carouselImages,
    interval: 5000,
  });

  navbar.init();
  newsletter.init();
  contactForm.init();
  new ArticlesPage();
  articleDetail.init(window.articlesData || {});
});
