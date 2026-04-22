import { Component, OnInit, inject } from '@angular/core';
import { CurrencyPipe, DatePipe } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { DashboardSummaryResponse, Investment, Transaction } from '../models';
import { FinanceApiService } from '../services/finance-api.service';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-dashboard-page',
  imports: [ReactiveFormsModule, CurrencyPipe, DatePipe],
  templateUrl: './dashboard-page.component.html',
  styleUrl: './dashboard-page.component.css',
})
export class DashboardPageComponent implements OnInit {
  private readonly fb = inject(FormBuilder);
  data: DashboardSummaryResponse | null = null;
  transactions: Transaction[] = [];
  investments: Investment[] = [];

  readonly transactionForm = this.fb.group({
    amount: [0, [Validators.required, Validators.min(0.01)]],
    type: ['expense', [Validators.required]],
    category: [''],
    description: ['', [Validators.required]],
    date: ['', [Validators.required]],
  });

  readonly investmentForm = this.fb.group({
    name: ['', [Validators.required]],
    amount_invested: [0, [Validators.required, Validators.min(0)]],
    current_value: [0, [Validators.required, Validators.min(0)]],
  });

  constructor(
    private readonly financeApiService: FinanceApiService,
    private readonly authService: AuthService,
    private readonly router: Router,
  ) {}

  ngOnInit(): void {
    this.refreshAll();
  }

  get progressPercentage(): number {
    return this.data?.goal.progress_percentage ?? 0;
  }

  get userEmail(): string {
    return this.authService.currentUser()?.email ?? '';
  }

  addTransaction(): void {
    if (this.transactionForm.invalid) {
      this.transactionForm.markAllAsTouched();
      return;
    }

    const payload = this.transactionForm.getRawValue() as Omit<Transaction, 'id'>;

    this.financeApiService.createTransaction(payload).subscribe(() => {
      this.transactionForm.reset({ amount: 0, type: 'expense', category: '', description: '', date: '' });
      this.refreshAll();
    });
  }

  deleteTransaction(id: number): void {
    this.financeApiService.deleteTransaction(id).subscribe(() => this.refreshAll());
  }

  addInvestment(): void {
    if (this.investmentForm.invalid) {
      this.investmentForm.markAllAsTouched();
      return;
    }

    const payload = this.investmentForm.getRawValue() as Omit<Investment, 'id' | 'profit_loss'>;

    this.financeApiService.createInvestment(payload).subscribe(() => {
      this.investmentForm.reset({ name: '', amount_invested: 0, current_value: 0 });
      this.refreshAll();
    });
  }

  deleteInvestment(id: number): void {
    this.financeApiService.deleteInvestment(id).subscribe(() => this.refreshAll());
  }

  logout(): void {
    this.authService.logout().subscribe({
      next: () => this.router.navigate(['/login']),
      error: () => {
        this.authService.forceLogout();
        this.router.navigate(['/login']);
      },
    });
  }

  private refreshAll(): void {
    this.financeApiService.getDashboardSummary().subscribe((response) => {
      this.data = response;
    });

    this.financeApiService.getTransactions().subscribe((response) => {
      this.transactions = response;
    });

    this.financeApiService.getInvestments().subscribe((response) => {
      this.investments = response;
    });
  }
}
