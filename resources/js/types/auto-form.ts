import type { Config } from '@/components/ui/auto-form'
import type { z } from 'zod'

export type AutoFormFieldConfig<T extends z.ZodObject<any>> = Config<z.infer<T>>
