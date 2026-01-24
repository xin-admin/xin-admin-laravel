import { useState, useCallback } from 'react';
import type { AxiosResponse, AxiosError } from 'axios';

interface UseRequestOptions<T> {
  manual?: boolean;
  onSuccess?: (data: T | undefined, response: AxiosResponse<API.ResponseStructure<T>>) => void;
  onError?: (error: AxiosError) => void;
  onFinally?: () => void;
}

interface UseRequestResult<T> {
  data: T | undefined;
  loading: boolean;
  success: boolean | null;
  error: AxiosError | null;
  run: (...args: any[]) => Promise<AxiosResponse<API.ResponseStructure<T>> | undefined>;
}

function useRequest<T = any>(
  apiFunction: (...args: any[]) => Promise<AxiosResponse<API.ResponseStructure<T>>>,
  options: UseRequestOptions<T> = {}
): UseRequestResult<T> {
  const { manual = false, onSuccess, onError, onFinally } = options;
  const [data, setData] = useState<T>();
  const [loading, setLoading] = useState(!manual);
  const [success, setSuccess] = useState<boolean | null>(null);
  const [error, setError] = useState<AxiosError | null>(null);

  const run = useCallback(
    async (...args: any[]) => {
      try {
        setLoading(true);
        setSuccess(null);
        setError(null);

        const response = await apiFunction(...args);

        setData(response.data.data);
        setSuccess(true);

        if (onSuccess) {
          onSuccess(response.data.data, response);
        }

        return response;
      } catch (err) {
        const axiosError = err as AxiosError;
        setError(axiosError);
        setSuccess(false);

        if (onError) {
          onError(axiosError);
        }

        return undefined;
      } finally {
        setLoading(false);
        if (onFinally) {
          onFinally();
        }
      }
    },
    [apiFunction, onSuccess, onError, onFinally]
  );

  // 自动执行
  useState(() => {
    if (!manual) {
      run();
    }
  });

  return { data, loading, success, error, run };
}

export default useRequest;