import { Injectable } from '@angular/core';
import { tap } from 'rxjs/operators';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpResponse,
  HttpErrorResponse
} from '@angular/common/http';

import {Observable} from 'rxjs';
import { AuthService } from './auth.service';

@Injectable({ providedIn: 'root' })
export class Interceptor implements HttpInterceptor {

  constructor(private auth: AuthService) { }
  private authToken = this.auth.getUserToken();
  intercept(
    request: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {
    const updatedRequest = request.clone({
      headers: request.headers.set('Authorization', this.authToken)
    });
    // console.log('Before making api call : ', updatedRequest);
    return next.handle(request).pipe(
      tap(
        event => {
          if (event instanceof HttpResponse) {
            // console.log('api call success :', event);
          }
        },
        error => {
          if (error instanceof HttpErrorResponse) {
            // console.log('api call error :', error);
          }
        }
      )
    );
  }
}
