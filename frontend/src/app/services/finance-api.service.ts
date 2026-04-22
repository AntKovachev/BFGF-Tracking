import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { DashboardSummaryResponse, Investment, Transaction } from '../models';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class FinanceApiService {
  private readonly apiBaseUrl = 'http://localhost:8000/api';

  constructor(private readonly http: HttpClient) {}

  getDashboardSummary(): Observable<DashboardSummaryResponse> {
    return this.http.get<DashboardSummaryResponse>(`${this.apiBaseUrl}/dashboard/summary`);
  }

  getTransactions(): Observable<Transaction[]> {
    return this.http.get<Transaction[]>(`${this.apiBaseUrl}/transactions`);
  }

  createTransaction(payload: Omit<Transaction, 'id'>): Observable<Transaction> {
    return this.http.post<Transaction>(`${this.apiBaseUrl}/transactions`, payload);
  }

  deleteTransaction(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiBaseUrl}/transactions/${id}`);
  }

  getInvestments(): Observable<Investment[]> {
    return this.http.get<Investment[]>(`${this.apiBaseUrl}/investments`);
  }

  createInvestment(payload: Omit<Investment, 'id' | 'profit_loss'>): Observable<Investment> {
    return this.http.post<Investment>(`${this.apiBaseUrl}/investments`, payload);
  }

  deleteInvestment(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiBaseUrl}/investments/${id}`);
  }
}
