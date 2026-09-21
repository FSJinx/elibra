async function handleRequest<T = any>(promise: Promise<any>, method: string, url: string): Promise<T> {
  try {
    const response = await promise
    return response.data as T
  } catch (e) {
    console.error(`Request failed [${method.toUpperCase()} ${url}]:`, e)
    throw e
  }
}

export function get<T = any>(url: string, params?: any, config?: any): Promise<T> {
  return handleRequest<T>(api.get(url, { params, ...config }), 'get', url)
}

export function del<T = any>(url: string, params?: any, config?: any): Promise<T> {
  return handleRequest<T>(api.delete(url, { params, ...config }), 'delete', url)
}

export function post<T = any>(url: string, payload?: any, config?: any): Promise<T> {
  return handleRequest<T>(api.post(url, payload, config), 'post', url)
}

export function put<T = any>(url: string, payload?: any, config?: any): Promise<T> {
  return handleRequest<T>(api.put(url, payload, config), 'put', url)
}

export function patch<T = any>(url: string, payload?: any, config?: any): Promise<T> {
  return handleRequest<T>(api.patch(url, payload, config), 'patch', url)
}
