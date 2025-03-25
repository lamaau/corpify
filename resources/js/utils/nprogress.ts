import type NProgress from 'nprogress'

// ZH: 创建一个NProgress实例的变量，初始值为null
// EN: Create a NProgress Instance, default null
let nProgressInstance: null | typeof NProgress = null

/**
 * ZH:
 * 动态加载NProgress库，并进行配置。
 * 此函数首先检查是否已经加载过NProgress库，如果已经加载过，则直接返回NProgress实例。
 * 否则，动态导入NProgress库，进行配置，然后返回NProgress实例。
 *
 * EN:
 * dynamic load NProgress library and configure it.
 * This function is used to load NProgress library and configure it.
 * If not, it will dynamically import the NProgress library, configure it, and then return the NProgress instance.
 *
 * @returns  NProgress实例的Promise对象。 NProgress instance's Promise object.
 */
async function loadNprogress() {
  if (nProgressInstance) {
    return nProgressInstance
  }
  nProgressInstance = await import('nprogress')
  nProgressInstance.configure({
    showSpinner: true,
    speed: 300,
  })
  return nProgressInstance
}

/**
 * ZH:
 * 开始显示进度条。
 * 此函数首先加载NProgress库，然后调用NProgress的start方法开始显示进度条。
 *
 * EN:
 * Start to show the progress bar.
 * This function first loads the NProgress library, then calls the NProgress start method to start showing the progress bar.
 */
async function startProgress() {
  const nprogress = await loadNprogress()
  nprogress?.start()
}

/**
 * ZH:
 * 停止显示进度条，并隐藏进度条。
 * 此函数首先加载NProgress库，然后调用NProgress的done方法停止并隐藏进度条。
 *
 * EN:
 * Stop showing the progress bar and hide it.
 * This function first loads the NProgress library, then calls the NProgress done method to stop and hide the progress bar.
 */
async function stopProgress() {
  const nprogress = await loadNprogress()
  nprogress?.done()
}

export { startProgress, stopProgress }
