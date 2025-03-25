import type { App } from 'vue'
import { createI18n } from 'vue-i18n'
// @ts-check
export function setupI18n(app: App) {
  const i18n = createI18n({
    locale: 'zh',
    fallbackLocale: 'en',
    messages: {
      zh: {
        premium: {
          premium: '会员计划',
          UpgradeNow: '现在升级',
        },
      },
      en: {
        premium: {
          premium: 'premium',
          UpgradeNow: 'Upgrade Now',
        },
      },
    },
  })
  app.use(i18n)
}
